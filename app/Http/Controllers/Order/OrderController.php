<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentMethods;
use App\Enums\TableStatus;
use App\Enums\UserRole;
use App\Events\OrderSubmittedToKitchen;
use App\Helpers\BillHelper;
use App\Helpers\KitchenHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\OrderHelper;
use App\Helpers\PDFHelper;
use App\Helpers\RestaurantHelper;
use App\Helpers\TableHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSubmitRequest;
use App\Http\Service\OrderService;
use App\Jobs\SaveAndPrintBill;
use App\Jobs\SaveAndPrintKOT;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function stepone()
    {
        if (!session()->has('tableData')) {
            return back();
        }
        $categoriesWithMenus = Category::with('menus')->get();

        $cart = session()->get('cart', []);

        $restaurantDetails = RestaurantHelper::getCachedRestaurantDetails();

        $currency = $restaurantDetails->currency_symbol;


        return view('orders.step-one', compact('categoriesWithMenus', 'cart', 'currency'));
    }

    public function submit(OrderSubmitRequest $request)
    {

        // Process the order
        $source = $request->source;

        $tableId = $request->tableId;
        $specialInstructions =  $request->specialInstructions ? implode(',',  $request->specialInstructions) :  null;
        $isTableOrder = true;
        $isPickUpOrder = $request->isPickUpOrder === "true" ? true : false;

        $order = collect($request->order);

        // if pick up order then set isTableOrder to false
        if ($isPickUpOrder) {
            $isTableOrder = false;
        }

        $commonData = collect([
            'tableId' => $tableId,
            'specialInstructions' => $specialInstructions,
            'isPickUpOrder' => $isPickUpOrder,
            'isTableOrder' => $isTableOrder,
        ]);

        if ($source === "waiter") {
            $orderData = $this->processWaiterOrder($request, $commonData);
        } else if ($source === "pos") {
            $orderData = $this->processPOSOrder($request, $commonData);
        }

        // New Order if Kitchen Module is enabled else by default order status marked as served
        $orderStatus = ModuleHelper::isKitchenModuleEnabled() ? OrderStatus::New : OrderStatus::Served;
        $orderData->put('status', $orderStatus);


        // insert order
        $response = $this->orderService->createOrder($orderData);

        // if order submission failed
        if ($response['status'] === "error") {
            return response()->json($response, 500);
        }

        $kot = $response['data'];

        // Table Marking

        if ($isTableOrder) {
            TableHelper::markTableAsRunning($tableId);
        }
        $kitchenModuleEnabled = ModuleHelper::isKitchenModuleEnabled();
        $printBillEnabled = env('BILL_PRINT_ENABLED');
        $printKOTEnabled = env('KOT_PRINT_ENABLED');


        // Send event to kitchen module
        if ($kitchenModuleEnabled) {
            try {
                event(new OrderSubmittedToKitchen($kot));
            } catch (Exception $e) {
                Log::error("Unable to connect with pusher api - internet issue");
            }
        }


        // Billing Process

        $billId = null;
        $discount = 0;
        $paymentMethod = $request->paymentMethod;
        $billTable = $request->billTable === "true" ? true : false;

        if ($isPickUpOrder) {
            //create bill for pick up order directly
            $billId = BillHelper::createPickUpBill($kot, null, $paymentMethod, $discount);
        }

        if ($isTableOrder && $billTable) {

            $billId = BillHelper::createTableBill($tableId,  null, $paymentMethod, $discount);

            // if printBill is enabled then print bill
            if ($printBillEnabled) {
                SaveAndPrintBill::dispatch($billId);
            }
        }

        // if printKOT is enabled then print KOT
        if ($printKOTEnabled) {
            SaveAndPrintKOT::dispatch($kot, $billId);
        }

        return response()->json(["status" => "success", "message" => "order Submitted successfully"]);
    }

    // Process Waiter Order
    private function processWaiterOrder(Request $request, $commonData)
    {
        $cart = session()->get('cart');
        $total = $cart["total"];
        unset($cart["total"]);
        $waiterSpecificData = collect([
            'waiterId' => auth()->user()->id,
            'orderItems' => $cart,
            'total' => $total,
            'orderType' => OrderType::DineIn->value,
        ]);
        return $commonData->merge($waiterSpecificData);
    }

    // Process POS Order
    private function processPOSOrder(Request $request, $commonData)
    {
        $order = $request->order;

        $orderType = $commonData['isPickUpOrder'] ? OrderType::Takeaway->value : OrderType::DineIn->value;

        $POSSpecificData = collect([
            'waiterId' => auth()->user()->id,
            'orderItems' => $order["orderItems"],
            'total' => $order["total"],
            'orderType' => $orderType,

        ]);

        return $commonData->merge($POSSpecificData);
    }

    public function markAsServed(Request $request)
    {
        $orderId = $request->orderId;
        try {
            $order = $this->orderService->markAsServed($request->orderId);
            return response()->json(['status' => 'success', 'message' => 'Order served successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function markAsPrepared(Request $request)
    {
        $orderId = $request->orderId;

        try {
            $order = $this->orderService->markAsReadyForPickup($request->orderId);
            return response()->json(['status' => 'success', 'message' => 'Order prepared successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function markAsClosed(Request $request)
    {
        $orderId = $request->orderId;

        try {
            $order = $this->orderService->markAsClosed($request->orderId);
            return response()->json(['status' => 'success', 'message' => 'Order closed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function KOTView()
    {
        /** @var \App\User */
        $waiter = auth()->user();

        $tables = Table::where('status', TableStatus::Running)->get();

        $orders = Order::with('orderDetails.menu')
            ->where('status', '!=', OrderStatus::Closed)
            ->orderBy('created_at', 'desc');

        if ($waiter->hasPermission(UserRole::Biller)) {
            //admin
            $orders = $orders->get();
        } else {
            $orders = $orders->where('waiter_id', $waiter->id)->get();
        }

        $waiterSyncTime = RestaurantHelper::getCachedRestaurantDetails()->waiter_sync_time * 1000;

        return view('orders.kot-view', compact('tables', 'orders', 'waiterSyncTime'));
    }
}

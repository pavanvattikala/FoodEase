<?php

namespace App\Http\Controllers\FrontEnd;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentMethods;
use App\Enums\TableStatus;
use App\Events\OrderSubmittedToKitchen;
use App\Helpers\BillHelper;
use App\Helpers\KitchenHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\PDFHelper;
use App\Helpers\RestaurantHelper;
use App\Helpers\TableHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SaveAndPrintBill;
use App\Jobs\SaveAndPrintKOT;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function stepone()
    {
        if (!session()->has('tableData')) {
            return back();
        }
        $categoriesWithMenus = Category::with('menus')->get();

        $cart = session()->get('cart', []);

        return view('orders.step-one', compact('categoriesWithMenus', 'cart'));
    }

    public function addToCart(Request $request)
    {

        $menuId = $request->menuId;
        $price = $request->price;
        $itemName = Menu::where('id', $menuId)->first()->name;

        $cart = session()->get('cart', []);

        // check if menu alredy exists
        if (array_key_exists($menuId, $cart)) {
            $cart[$menuId]['quantity']++;
            $cart[$menuId]['total'] = $cart[$menuId]['price'] * $cart[$menuId]['quantity'];
        }

        if (!array_key_exists($menuId, $cart)) {
            // add new menu item
            $cart[$menuId] = [
                'id' => $menuId,
                'name' => $itemName,
                'quantity' => 1,
                'price' => $price,
                'total' => $price,
            ];
        }

        $cart['total'] = isset($cart['total']) ? $cart['total'] + $price : $price;

        // Update the cart in the session
        session()->put('cart', $cart);

        return response()->json(['message' => 'Item added to cart']);
    }



    public function removeFromCart(Request $request)
    {

        $menuId = $request->menuId;
        $price = $request->price;

        $cart = session()->get('cart', []);

        // check if menu alredy exists
        if (array_key_exists($menuId, $cart) && $cart[$menuId]['quantity'] > 0) {
            $cart[$menuId]['quantity']--;
            $cart[$menuId]['total'] = $cart[$menuId]['price'] * $cart[$menuId]['quantity'];

            // If the quantity becomes zero, remove the item from the cart
            if ($cart[$menuId]['quantity'] <= 0) {
                unset($cart[$menuId]);
            }

            // Subtract the price of the removed item from the total
            $cart['total'] -= $price;

            // Update the cart in the session
            session()->put('cart', $cart);

            return response()->json(['message' => 'Item removed from cart']);
        }

        return response()->json(['message' => 'Item not found in cart'], 404);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $predefinedNotes = config('predefined_options.notes');
        return view('orders.cart', compact('cart', 'predefinedNotes'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        session()->forget('tableData');
        session()->forget('reOrder');

        return response()->json("cart cleared sucessfully");
    }
    public function submit(Request $request)
    {

        $source = $request->source;
        $printBillEnabled = $request->printBill == "true" ? true : false;
        $printKOTEnabled = $request->printKOT == "true" ? true : false;
        $reOrder = $request->reOrder == "true" ? true : false;
        $order = collect($request->order);

        $tableId = $order->get('tableId');

        $specialInstructions = $order->get('specialInstructions') ? implode(',', $order->get('specialInstructions')) :  null;

        $commonData = collect([
            'tableId' => $order->get('tableId'),
            'specialInstructions' => $specialInstructions,
            "reOrder" => false,
            'isPickUpOrder' => $order->get('tableId') ? false : true
        ]);

        if ($source == "waiter") {
            $orderData = $this->processWaiterOrder($request, $commonData);
        } else if ($source == "pos") {
            $orderData = $this->processPosOrder($request, $commonData);
        }

        $kot =  $this->insertOrder($orderData);

        if ($kot == "failed") {
            return response()->json(["status" => "error", "message" => "order Submission Failed"]);
        }

        // if order is not a pickup order then mark table as running
        // if order is reOrder then do not mark table as Taken
        $isPickUpOrder = $orderData->get('isPickUpOrder');

        if (!$isPickUpOrder) {
            if ($reOrder) {
                TableHelper::markTableAsRunning($tableId);
            } else {
                TableHelper::markTableAsTaken($tableId);
            }
        }

        if (ModuleHelper::isKitchenModuleEnabled()) {
            event(new OrderSubmittedToKitchen($kot));
        }


        if ($printKOTEnabled) {
            SaveAndPrintKOT::dispatch($kot);
        }
        if ($printBillEnabled) {
            //create bill
            $paymentMethod = PaymentMethods::CASH->value;
            $discount = 0;
            $billId = null;
            if ($orderData->get('isPickUpOrder')) {
                $billId = BillHelper::createPickUpBill($kot, null, $paymentMethod, $discount);
            } else {
                $tableId = $orderData->get('tableId');
                $billId = BillHelper::createTableBill($tableId,  null, $paymentMethod, $discount);
            }

            SaveAndPrintBill::dispatch($billId);
        }

        $this->clearCart();

        return response()->json(["status" => "success", "message" => "order Submitted successfully"]);
    }

    private function insertOrder($orderData)
    {

        $kot = KitchenHelper::generateKOT();
        $total = $orderData->get("total");
        $tableId = $orderData->get("tableId");
        $status = $orderData->get("status");
        $specialInstructions = $orderData->get("specialInstructions");
        $orderType = $orderData->get("orderType");
        $waiterId = $orderData->get("waiterId");
        $orderItems = $orderData->get("orderItems");


        $orderObject = [
            "KOT" => $kot,
            "total" => $total,
            "table_id" => $tableId,
            "status" => $status,
            "special_instructions" => $specialInstructions,
            "order_type" => $orderType,
            "waiter_id" => $waiterId
        ];

        try {
            DB::beginTransaction();

            $order = Order::create($orderObject);

            foreach ($orderItems as $key => $item) {
                $orderDetail = new OrderDetail([
                    'order_id' => $order->id,
                    'menu_id' => $item["id"],
                    'quantity' => $item['quantity'],
                ]);

                $order->orderDetails()->save($orderDetail);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return "failed";
        }

        return $kot;
    }

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
            "status" => OrderStatus::New->value,
        ]);
        return $commonData->merge($waiterSpecificData);
    }
    private function processPosOrder(Request $request, $commonData)
    {
        $order = $request->order;

        $status = OrderStatus::New->value;
        $posSpecificData = collect([
            'customer' => $order["customer"],
            'waiterId' => auth()->user()->id,
            'orderItems' => $order["orderItems"],
            'total' => $order["total"],
            'orderType' => session()->get("orderType"),
            'status' => $status

        ]);

        return $commonData->merge($posSpecificData);
    }

    public function orderHistory()
    {
        /** @var \App\User */
        $waiter = auth()->user();

        $orders = Order::with('orderDetails.menu')
            ->where('created_at', '>=', Carbon::today())
            ->where('status', OrderStatus::Closed)
            ->orderBy('created_at', 'desc');


        if ($waiter->hasPermission(1)) {
            //admin
            $orders = $orders->get();
        } else {
            $orders = $orders->where('waiter_id', $waiter->id)->get();
        }

        return view('orders.order-history', ['orders' => $orders]);
    }


    public function runningOrders()
    {
        /** @var \App\User */
        $waiter = auth()->user();

        $orders = Order::with('orderDetails.menu')
            ->where('created_at', '>=', Carbon::today())
            ->where('status', '!=', OrderStatus::Closed)
            ->orderBy('created_at', 'desc');

        if ($waiter->hasPermission(1)) {
            //admin
            $orders = $orders->get();
        } else {
            $orders = $orders->where('waiter_id', $waiter->id)->get();
        }

        return view('orders.running-orders', ['orders' => $orders]);
    }

    public function readyForPickUp()
    {

        /** @var \App\User */
        $waiter = auth()->user();

        $orders = Order::with('orderDetails.menu')
            ->where('created_at', '>=', Carbon::today())
            ->where('status', OrderStatus::ReadyForPickup)
            ->orderBy('updated_at', 'desc');

        if ($waiter->hasPermission(1)) {
            //admin
            $orders = $orders->get();
        } else {
            $orders = $orders->where('waiter_id', $waiter->id)->get();
        }

        $waiterSyncTime = RestaurantHelper::getCachedRestaurantDetails()->waiter_sync_time * 1000;

        return view('orders.ready-for-pickup', compact('orders', 'waiterSyncTime'));
    }

    public function markAsServed(Request $request)
    {
        $orderId = $request->orderId;

        $order = new Order();
        $order = $order->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = OrderStatus::Served;

        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order served  successfully'], 200);
    }
}

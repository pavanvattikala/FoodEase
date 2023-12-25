<?php

namespace App\Http\Controllers\FrontEnd;

use App\Enums\OrderStatus;
use App\Events\OrderSubmittedToKitchen;
use App\Helpers\RestaurantHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Helpers\TableHelper;
use App\Models\Order;
use Error;
use PhpParser\Node\Expr\New_;

class OrderController extends Controller
{
    public function stepone(Request $request)
    {
        if(!session()->has('tableId')){
            return back();
        }
        $categoriesWithMenus = Category::with('menus')->get();

        $cart = session()->get('cart', []);

        return view('orders.step-one',compact('categoriesWithMenus','cart'));
    }

    public function addToCart(Request $request){

        $menuId = $request->menuId;
        $price = $request->price;
        $itemName = Menu::where('id', $menuId)->first()->name;
        
        $cart = session()->get('cart', []);
        
        // check if menu alredy exists
        if (array_key_exists($menuId, $cart)) {
            $cart[$menuId]['quantity']++;
        } else {
            // add new menu item
            $cart[$menuId] = [
                'name' => $itemName,
                'quantity' => 1,
                'price' => $price,
            ];
        }
        
        $cart['total'] = isset($cart['total']) ? $cart['total'] + $price : $price;
        
        // Update the cart in the session
        session()->put('cart', $cart);
        
        return response()->json(['message' => 'Item added to cart']);
        
    }
    


    public function removeFromCart(Request $request) {

        $menuId = $request->menuId;
        $price = $request->price;

        $cart = session()->get('cart', []);

        // check if menu alredy exists
        if (array_key_exists($menuId, $cart) && $cart[$menuId]['quantity'] > 0) {
            $cart[$menuId]['quantity']--;

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

    public function cart() {
        $cart = session()->get('cart', []);

        return view('orders.cart', ['cart' => $cart]);
    }

    public function clearCart() {
        session()->forget('cart');
        session()->forget('tableId');
        session()->forget('reOrder');

        return response()->json("cart cleared sucessfully");
    }

    public function submit(Request $request){

        // submit the order to kitchen

        $waiterId = auth()->user()->id;

        $tableId=null;

        $reOrder=false;

        
        if(session()->has("tableId")){
            $tableId = intval(session()->get("tableId"));
        }

        else{
            return response()->json(['error' => 'Table not selected.'], 422);
        }


        if(session()->has("reOrder")){
            $reOrder = boolval(session()->get("reOrder"));
        }
 

        $cart = session()->get('cart', []);


        $cart["waiterId"] = $waiterId;

        $cart["tableId"] = $tableId;

        $cart["reOrder"] = $reOrder;

      


        //create order submit event
        event(new OrderSubmittedToKitchen($cart));
        
        session()->forget('cart');
        session()->forget('tableId');
        session()->forget('reOrder');

        return response()->json(["message"=> "order placed successfully"]);
    }

    /**
     * Retrieves the order history for the authenticated waiter.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function orderHistory()
    {
        $waiterId = auth()->user()->id;
        $orders = Order::with('orderDetails.menu') 
            ->where('waiter_id', $waiterId)
            ->where('status', OrderStatus::Closed)
            ->orderBy('created_at', 'desc')
            ->get();

        // You can return the orders to a view or process them as needed
        return view('orders.order-history', ['orders' => $orders]);
    }

    /**
     * Get the running orders for the authenticated waiter.
     *
     * @return \Illuminate\View\View
     */
    public function runningOrders()
    {
        // running orders
        $waiterId = auth()->user()->id;
        $orders = Order::with('orderDetails.menu') 
            ->where('waiter_id', $waiterId)
            ->where('status',OrderStatus::New)
            ->orWhere('status',OrderStatus::Processing)
            ->orWhere('status',OrderStatus::Served)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('orders.order-history', ['orders' => $orders]);
    }
    /**
     * Get the orders that are ready for pickup.
     *
     * @return \Illuminate\View\View
     */
    public function readyForPickUp()
    {
        $waiterId = auth()->user()->id;
        $orders = Order::with('orderDetails.menu') 
            ->where('waiter_id', $waiterId)
            ->where('status', OrderStatus::ReadyForPickup)
            ->orderBy('updated_at', 'desc')
            ->get();

        

        $waiter_sync_time = RestaurantHelper::getCachedRestaurantDetails()->waiter_sync_time*1000;

        return view('orders.order-history', ['orders' => $orders,"waiter_sync_time"=>$waiter_sync_time]);
        
    }

    public function markAsServed(Request $request){
        $orderId = $request->orderId;       
    
        // Retrieve the order from the database
        $order = Order::find($orderId);
    
        if (!$order) {
            // Handle the case where the order is not found
            return response()->json(['error' => 'Order not found'], 404);
        }        
    
        $order->status = OrderStatus::Served;

        $order->save();
    
        // You can return a response if needed
        return response()->json(['status'=>'success','message' => 'Order served  successfully'],200);

    }

    

}
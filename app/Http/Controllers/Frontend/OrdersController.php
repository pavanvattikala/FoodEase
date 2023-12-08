<?php

namespace App\Http\Controllers\FrontEnd;

use App\Enums\TableStatus;
use App\Events\OrderSubmittedToKitchen;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Table;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrdersController extends Controller
{
    public function stepone(Request $request)
    {
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

        return response()->json("cart cleared sucessfully");
    }

    public function submit(Request $request){
        $cart = session()->get('cart', []);

        //create order submit event
        event(new OrderSubmittedToKitchen($cart));
        
        session()->forget('cart');

        return route("waiter.waiter.home");
    }
}
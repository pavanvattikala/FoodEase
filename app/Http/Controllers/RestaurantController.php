<?php

namespace App\Http\Controllers;

use App\Helpers\RestaurantHelper;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class RestaurantController extends Controller
{
    //

    public function updateConfig(Request $request)
    {
        $id = $request->id;
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'GST' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'pending_order_sync_time' => 'required|in:5,15,30,60',
            'waiter_sync_time' => 'required|numeric|in:5,15,30,60',
            'minimum_delivery_time' => 'required|numeric|min:1',
            'minimum_preparation_time' => 'required|numeric|min:1',
            'order_live_view' => 'required|in:asc,desc',
            'kot_live_view' => 'required|in:asc,desc',
        ]);

        // Update the configuration values

        Restaurant::find($id)->update($validatedData);

        RestaurantHelper::refreshAndCacheRestaurantDetails();


        return redirect()->route('restaurant.show.config')->with('success', 'Configuration updated successfully.');
    }

    public function showConfig()
    {
        $restaurantConfig = Restaurant::first();

        return view('restaurant.show-config', compact('restaurantConfig'));
    }
}

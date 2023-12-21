<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RestaurantController extends Controller
{
    //

    public function updateConfig(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'address' => 'required|string',
            // Add validation rules for other configurations as needed
        ]);

        // Update the configuration values
        foreach ($validatedData as $key => $value) {
            config(["restaurant.$key" => $value]);
        }

        return redirect()->route('restaurant-config.show')->with('success', 'Configuration updated successfully.');
    }

    public function showConfig()
    {
        $restaurantConfig = config('restaurant');

        return view('restaurant.show-config', compact('restaurantConfig'));
    }
}

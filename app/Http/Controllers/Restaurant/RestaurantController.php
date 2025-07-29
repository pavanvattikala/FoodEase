<?php

namespace App\Http\Controllers\Restaurant;

use App\Helpers\RestaurantHelper;
use App\Http\Controllers\Controller;
use App\Http\Service\RestaurantService;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // For logging errors
use Illuminate\Support\Facades\Config; // Used for checking Pusher config
use Illuminate\Support\Facades\DB; // For database operations

class RestaurantController extends Controller
{
    protected $restaurantService;

    // constructor to apply middleware
    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * Display the restaurant configuration.
     *
     * @return \Illuminate\View\View
     */
    public function showConfig()
    {
        $restaurantConfig = $this->restaurantService->getRestaurantDetails();

        $moduleStatus = [
            'waiter_module_enabled' => (bool)$restaurantConfig->waiter_module_enabled,
            'kitchen_module_enabled' => (bool)$restaurantConfig->kitchen_module_enabled,
        ];

        return view('restaurant.show-config', compact('restaurantConfig', 'moduleStatus'));
    }

    /**
     * Update the general restaurant configuration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateConfig(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'GST' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'pending_order_sync_time' => 'required|in:5,15,30,60',
            'waiter_sync_time' => 'required|numeric|in:5,15,30,60',
            'minimum_delivery_time' => 'required|numeric|min:300|max:1200', // In seconds (5-20 minutes)
            'minimum_preparation_time' => 'required|numeric|min:300|max:1200', // In seconds (5-20 minutes)
            'order_live_view' => 'required|in:asc,desc',
            'kot_live_view' => 'required|in:asc,desc',
            'biller_printer' => 'nullable|string',
            'kitchen_printer' => 'nullable|string',
        ]);

        $restaurantConfig = Restaurant::firstOrCreate([]); // Ensure a record exists
        $restaurantConfig->update($validatedData);

        RestaurantHelper::refreshAndCacheRestaurantDetails(); // Assuming this helper exists and works

        return redirect()->route('admin.restaurant.show.config')->with('success', 'Restaurant configurations updated successfully!');
    }

    /**
     * Enable the Waiter module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enableWaiterModule(Request $request)
    {
        try {
            $restaurantConfig = $this->restaurantService->getRestaurantDetails();
            $restaurantConfig->update(['waiter_module_enabled' => true]);

            return response()->json(['success' => true, 'message' => 'Waiter module enabled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error enabling Waiter module: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to enable Waiter module: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Disable the Waiter module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disableWaiterModule(Request $request)
    {
        try {
            $restaurantConfig = $this->restaurantService->getRestaurantDetails();
            $restaurantConfig->update(['waiter_module_enabled' => false]);

            return response()->json(['success' => true, 'message' => 'Waiter module disabled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error disabling Waiter module: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to disable Waiter module: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Enable the Kitchen module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function enableKitchenModule(Request $request)
    {

        try {

            Log::info('Checking Kitchen configurations...');

            $isPusherKeySet = config('broadcasting.connections.pusher.key');
            $isPusherSecretSet = config('broadcasting.connections.pusher.secret');
            $isPusherAppIdSet = config('broadcasting.connections.pusher.app_id');
            $isPusherClusterSet = config('broadcasting.connections.pusher.options.cluster');

            $pusherNotConfigured = !$isPusherKeySet || !$isPusherSecretSet || !$isPusherAppIdSet || !$isPusherClusterSet;

            if ($pusherNotConfigured) {
                return response()->json([
                    'success' => false,
                    'error' => 'PUSHER Service is not configured properly. Please set the Pusher credentials in the .env file.'
                ], 400);
            }

            Log::info('Pusher configurations are set up correctly.');

            $restaurantConfig = $this->restaurantService->getRestaurantDetails();;

            $restaurantConfig->update(['kitchen_module_enabled' => true]);

            return response()->json(['success' => true, 'message' => 'Kitchen module enabled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error enabling Kitchen module: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to enable Kitchen module: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Disable the Kitchen module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disableKitchenModule(Request $request)
    {
        try {
            $restaurantConfig = $this->restaurantService->getRestaurantDetails();;
            $restaurantConfig->update(['kitchen_module_enabled' => false]);

            return response()->json(['success' => true, 'message' => 'Kitchen module disabled successfully!']);
        } catch (\Exception $e) {
            Log::error('Error disabling Kitchen module: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to disable Kitchen module: ' . $e->getMessage()], 500);
        }
    }
}

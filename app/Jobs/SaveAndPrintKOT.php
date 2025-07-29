<?php

namespace App\Jobs;

use App\Helpers\PDFHelper;
use App\Helpers\Printers\ThermalPrinter;
use App\Http\Service\RestaurantService;
use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveAndPrintKOT implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $KOT;
    private $kOTPath;
    private $billId;
    protected $restaurantService;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($KOT, $billId)
    {
        $this->KOT = $KOT;
        $this->billId = $billId;

        $this->restaurantService = new RestaurantService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $kitchenPrinter = $this->restaurantService->getKitchenPrinter();

        try {
            $printer = new ThermalPrinter($kitchenPrinter);
            $printer->printKOT($this->KOT, $this->billId);
            Log::info("KOT Printed " . $this->KOT);
        } catch (\Exception $e) {
            Log::error("Error in printing KOT " . $e->getMessage());
        }
    }
}

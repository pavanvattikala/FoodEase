<?php

namespace App\Jobs;

use App\Helpers\PDFHelper;
use App\Helpers\Printers\ThermalPrinter;
use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Service\RestaurantService;
use Illuminate\Support\Facades\Log;

class SaveAndPrintBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $billId;
    private $printDuplicateBill;
    protected $restaurantService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($billId, $printDuplicateBill = false)
    {
        $this->billId = $billId;
        $this->printDuplicateBill = $printDuplicateBill;

        Log::info("Bill Printing Started " . $billId);

        $this->restaurantService = new RestaurantService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $billerPrinter = $this->restaurantService->getBillerPrinter();

        try {
            $printer = new ThermalPrinter($billerPrinter);

            if ($this->printDuplicateBill) {
                Log::info("Duplicate Bill Requested" . $this->billId);
                $printer->printDuplicateBill($this->billId);
            } else {
                $printer->printBill($this->billId);
            }

            Log::info("Bill Printed " . $this->billId);
        } catch (\Exception $e) {
            Log::error("Error in printing Bill " . $e->getMessage());
        }
    }
}

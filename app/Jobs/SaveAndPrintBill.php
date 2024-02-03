<?php

namespace App\Jobs;

use App\Helpers\PDFHelper;
use App\Helpers\Printers\ThermalPrinter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveAndPrintBill implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $billId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($billId)
    {
        $this->billId = $billId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $billerPrinter = config("predefined_options.printer.pos");

        try {
            $printer = new ThermalPrinter($billerPrinter);

            $printer->printBill($this->billId);

            Log::info("Bill Printed " . $this->billId);
        } catch (\Exception $e) {
            Log::error("Error in printing Bill " . $e->getMessage());
        }
    }
}

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

class SaveAndPrintKOT implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $KOT;
    private $kOTPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($KOT)
    {
        $this->KOT = $KOT;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $kitchenPrinter = config("predefined_options.printer.kitchen");

        $printer = new ThermalPrinter($kitchenPrinter);

        $printer->printKOT($this->KOT);

        Log::info("KOT Printed " . $this->KOT);
    }
}

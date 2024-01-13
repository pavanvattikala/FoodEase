<?php

namespace App\Jobs;

use App\Helpers\PDFHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        $this->kOTPath =  PDFHelper::saveKOTToDisk($this->KOT);
        PDFHelper::printKOT($this->kOTPath);
    }
}

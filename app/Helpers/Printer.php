<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Printer
{
    private $pdfToPrinterPath;
    private $printerName;
    private $serverName;


    public function __construct($printerName)
    {
        $this->pdfToPrinterPath = Storage::path('printPdf.exe');

        $this->serverName = gethostname();
        $this->printerName = "\\\\" . $this->serverName  . "\\" . $printerName . "-pos";
    }

    public function printToNetworkPrinter($pdfFilePath)
    {
        try {
            $command = "\"$this->pdfToPrinterPath\" \"$pdfFilePath\" \"$this->printerName\" focus=/s";

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                Log::info("PDF sent to printer successfully.");
            } else {
                Log::error("Error printing PDF. Return code: $returnCode");
            }
        } catch (Exception $e) {
            Log::error("Exception: " . $e->getMessage());
        } finally {
            Log::info("Print done");
        }
    }
}

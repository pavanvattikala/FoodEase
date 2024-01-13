<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Printer
{
    private $pdfToPrinterPath;
    private $printerName;
    private $printerIp;


    public function __construct($printerName, $printerIp)
    {
        $this->pdfToPrinterPath = Storage::path('printPdf.exe');

        $this->printerName =  $printerName;

        $this->printerIp = $printerIp;
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
    public function printToThermalPrinter($txtFilePath)
    {
        try {
            Log::info("printer is" . $this->printerName);


            $command = "LPR -S " . $this->printerIp . " -P " . $this->printerName . ' "' . $txtFilePath . '"';

            Log::info("Command: " . $command);

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                Log::info("TXT sent to printer successfully.");
            } else {
                Log::error("Error printing TXT. Return code: $returnCode");
            }
        } catch (Exception $e) {
            Log::error("Exception: " . $e->getMessage());
        } finally {
            Log::info("Print done");
        }
    }
}

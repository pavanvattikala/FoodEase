<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class MyPrinter
{
    private $printerName;
    public function __construct($printerName)
    {
        $this->printerName = $printerName;
    }

    public function printBill($billId)
    {
        
    }

    public function printBill($orderDetails)
    {
    }

   
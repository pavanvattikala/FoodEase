<?php

namespace App\Helpers\Printers;

use Mike42\Escpos\Printer;

class KOTPrinter

{
    private Printer $printer;
    private $kotDetails;

    public function __construct(Printer $printer, $kotDetails)
    {
        $this->printer = $printer;
        $this->kotDetails = $kotDetails;
    }
    public function print()
    {
        // Header
        $this->printer->text("KOT ORDER\n");
        $this->printer->text("KOT: {$this->kotDetails['kotId']}\n");
        $this->printer->text("Time: {$this->kotDetails['time']} Dine In: {$this->kotDetails['dineIn']}\n");
        $this->printer->text("Waiter: {$this->kotDetails['waiter']}\n");
        $this->printer->text("------------------------------------\n");

        // Items
        $this->printer->text("Qty Item\n");
        $this->printer->text("------------------------------------\n");
        foreach ($this->kotDetails['items'] as $item) {
            $this->printer->text($item);
        }

        // Total
        $this->printer->text("------------------------------------\n");
        $this->printer->text("Total Qty : {$this->kotDetails['totalQty']}\n");
        $this->printer->text("------------------------------------\n");

        // Cut the receipt
        $this->printer->cut();

        $this->printer->close();
    }
}

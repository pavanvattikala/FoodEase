<?php

namespace App\Helpers\Printers;

use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer;

class KOTPrinter

{
    private Printer $printer;
    private $KOTDetails;
    private $orderDetails;

    public function __construct(Printer $printer,   $KOTDetails, $orderDetails)
    {
        $this->printer = $printer;
        $this->KOTDetails = $KOTDetails;
        $this->orderDetails = $orderDetails;
    }
    public function print()
    {
        // Header
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->setEmphasis(true);
        $this->printer->setTextSize(2, 2);
        $this->printer->text("{$this->KOTDetails->KOT}\n");
        $this->printer->setTextSize(1, 1);
        $this->printer->setEmphasis(false);
        $this->printer->feed();
        if ($this->KOTDetails->table_id == null) {
            $this->printer->text("Date: {$this->KOTDetails->created_at->format('Y-m-d h:i A')}          Take Away\n");
        } else {
            $this->printer->text("Date: {$this->KOTDetails->created_at->format('Y-m-d h:i A')}          Dine In: {$this->KOTDetails->table->name}\n");
        }
        $this->printer->text("Waiter: {$this->KOTDetails->waiter->name}\n");

        // -----------------------------------
        $seperator = $this->getSeperator();

        // -----------------------------------
        $this->printer->text($seperator);

        // Total 45
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);


        $nameCol = 40;
        $qtyCol = 5;


        // build the heading
        $heading_col = str_pad("Item", $nameCol, ' ', STR_PAD_RIGHT);
        $heading_col .= str_pad("Qty", $qtyCol, ' ', STR_PAD_RIGHT);

        $this->printer->text($heading_col . "\n");

        // -----------------------------------
        $this->printer->text($seperator);

        foreach ($this->orderDetails as $name => $quantity) {
            $item = new KOTItem($name, $quantity);
            $this->printer->text($item);
        }



        // -----------------------------------
        $this->printer->text($seperator);

        // Total
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

        $this->printer->text("Total Qty : {$this->orderDetails->count()}\n");

        // -----------------------------------
        $this->printer->text($seperator);
        // Cut the receipt
        $this->printer->cut();

        $this->printer->close();
    }

    public function getSeperator(): string
    {
        //45
        return "---------------------------------------------\n";
    }
}

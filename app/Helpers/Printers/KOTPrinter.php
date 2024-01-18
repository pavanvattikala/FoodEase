<?php

namespace App\Helpers\Printers;

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
        $this->printer->text("KOT ORDER\n");
        $this->printer->text("KOT: {$this->KOTDetails->KOT}\n");
        if ($this->KOTDetails->table_id == null) {
            $this->printer->text("Date: {$this->KOTDetails->created_at}  Take Away}\n");
        } else {
            $this->printer->text("Date: {$this->KOTDetails->created_at} Dine In: {$this->KOTDetails->table->name}\n");
        }
        $this->printer->text("Waiter: {$this->KOTDetails->waiter->name}\n");
        $this->printer->text("------------------------------------\n");

        // Items
        $this->printer->text("Qty Item\n");
        $this->printer->text("------------------------------------\n");
        foreach ($this->orderDetails as $order) {
            $item = new KOTItem($order['name'], $order['quantity']);
            $this->printer->text($item);
        }

        // Total
        $this->printer->text("------------------------------------\n");
        $this->printer->text("Total Qty : {$this->orderDetails->count()}\n");
        $this->printer->text("------------------------------------\n");

        // Cut the receipt
        $this->printer->cut();

        $this->printer->close();
    }
}

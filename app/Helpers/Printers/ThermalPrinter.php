<?php

namespace App\Helpers\Printers;

use App\Helpers\BillHelper;
use App\Helpers\KitchenHelper;
use App\Models\Bill;
use App\Models\Order;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ThermalPrinter
{
    private $printerName;
    private $printer;

    public function __construct($printerName)
    {
        $this->printerName = $printerName;
        $this->printer = $this->getPrinterObject();
    }

    public function getPrinterObject()
    {
        $connector = new WindowsPrintConnector($this->printerName);
        $printer = new Printer($connector);
        return $printer;
    }

    public function printBill($billId)
    {

        $billDetails = Bill::where('id', $billId)
            ->with('table')
            ->first();
        $orderDetails =  BillHelper::getBillOrders($billId);

        $billPrinter = new BillPrinter($this->printer, $billDetails, $orderDetails);
        $billPrinter->print();
    }

    public function printKOT($kot)
    {
        $orderDetails = KitchenHelper::getKOTOrders($kot);
        $KOTDetails =  Order::with('waiter')
            ->with('table')
            ->where('kot', $kot)->first();
        $kotPrinter = new KOTPrinter($this->printer, $KOTDetails, $orderDetails);
        $kotPrinter->print();
    }
}

<?php

namespace App\Helpers\Printers;

use App\Helpers\BillHelper;
use App\Helpers\RestaurantHelper;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use OrderItem;

class BillPrinter
{
    private $billDetails;
    private $restaurentName;
    private $restaurentAddress;
    private $restaurentPhone;
    private $tagLine;
    private Printer $printer;
    private $orderDetails;

    public function __construct(Printer $printer, $billDetails, $orderDetails)
    {
        $restarurentDetais = RestaurantHelper::getCachedRestaurantDetails();
        $this->restaurentName = $restarurentDetais->name;
        $this->restaurentAddress = $restarurentDetais->address;
        $this->restaurentPhone = $restarurentDetais->phone;
        $this->tagLine = $restarurentDetais->tagline;
        $this->billDetails = $billDetails;
        $this->orderDetails = $orderDetails;
        $this->printer = $printer;
    }

    public function print()
    {
        // Header
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text($this->restaurentName . "\n");
        $this->printer->text($this->restaurentAddress . "\n");
        $this->printer->text($this->restaurentPhone . "\n");
        $this->printer->feed();

        // Order details
        $this->printer->text("----------------------------------\n");
        $this->printer->text("Name: " . $this->billDetails->customer_name ? $this->billDetails->customer_name : ' ' . "\n");
        $this->printer->text("----------------------------------\n");

        if ($this->billDetails->table_id == null) {
            $this->printer->text("Date: {$this->billDetails->created_at}  Take Away}\n");
        } else {
            $this->printer->text("Date: {$this->billDetails->created_at} Dine In: {$this->billDetails->table->name}\n");
        }
        $this->printer->text("Bill ID: {$this->billDetails->bill_id}\n");
        $this->printer->text("------------------------------------\n");

        // Items
        $this->printer->text("Item            Qty  Price    Amount\n");
        $this->printer->text("------------------------------------\n");


        foreach ($this->orderDetails as $name => $details) {
            $item = new OrderItem($name, $details['quantity'], $details['price'], $details['total']);
            $this->printer->text($item);
        }

        // Total
        $this->printer->text("------------------------------------\n");
        $this->printer->text("Total Qty :{$this->orderDetails->count()}      Sub Total {$this->billDetails->bill_amount}\n");
        $this->printer->text("------------------------------------\n");
        $this->printer->text("               Grand Total  $ {$this->billDetails->grand_total}\n");
        $this->printer->text("------------------------------------\n");
        $this->printer->text("       **{$this->tagLine}**\n");
        $this->printer->text("    **Thank You For Dining With Us**\n");

        // Cut the receipt and open the cash drawer
        $this->printer->cut();
        $this->printer->pulse();

        $this->printer->close();
    }
}

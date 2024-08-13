<?php

namespace App\Helpers\Printers;

use App\Helpers\RestaurantHelper;
use Mike42\Escpos\Printer;
use App\Helpers\Printers\OrderItem;

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
        $this->printer->setEmphasis(true);
        $this->printer->setTextSize(2, 2);
        $this->printer->text($this->restaurentName . "\n");
        $this->printer->setTextSize(1, 1);
        $this->printer->setEmphasis(false);
        $this->printer->text($this->restaurentAddress . "\n");
        $this->printer->text($this->restaurentPhone . "\n");
        $this->printer->feed();


        // -----------------------------------
        $seperator = $this->getSeperator();

        // Order details

        if ($this->billDetails->customer_name) {
            $this->printer->text($seperator);
            $this->printer->text("Name: {$this->billDetails->customer_name} \n");
        }

        // -----------------------------------
        $this->printer->text($seperator);

        if ($this->billDetails->table_id == null) {
            $this->printer->text("Date: {$this->billDetails->created_at->format('Y-m-d h:i A')}       Take Away\n");
        } else {
            $this->printer->text("Date: {$this->billDetails->created_at->format('Y-m-d h:i A')}       Dine In: {$this->billDetails->table->name}\n");
        }
        $this->printer->text("\n");
        $this->printer->text("Bill ID: {$this->billDetails->bill_id}\n");

        // -----------------------------------
        $this->printer->text($seperator);

        // Items
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $nameCol = 25;
        $qtyCol = 5;
        $priceCol = 5;
        $amountCol = 10;

        // build the heading
        $heading_col = str_pad("Item", $nameCol, ' ', STR_PAD_RIGHT);
        $heading_col .= str_pad("Qty", $qtyCol, ' ', STR_PAD_RIGHT);
        $heading_col .= str_pad("Price", $priceCol, ' ', STR_PAD_LEFT);
        $heading_col .= str_pad("Amount", $amountCol, ' ', STR_PAD_LEFT);


        $this->printer->text($heading_col . "\n");

        // -----------------------------------
        $this->printer->text($seperator);


        foreach ($this->orderDetails as $name => $details) {
            $item = new OrderItem($name, $details['quantity'], $details['price'], $details['total']);
            $this->printer->text($item);
            $this->printer->text("\n");
        }

        // -----------------------------------
        $this->printer->text($seperator);
        $this->printer->text("Total Qty :{$this->orderDetails->count()}            Sub Total {$this->billDetails->bill_amount}\n");
        // -----------------------------------
        $this->printer->text($seperator);
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

        $this->printer->text("Grand Total  Rs {$this->billDetails->grand_total}\n");
        // -----------------------------------
        $this->printer->text($seperator);
        $this->printer->text("**  {$this->tagLine}  **\n");
        $this->printer->text("**  Thank You For Dining With Us  **\n");

        // Cut the receipt and open the cash drawer
        $this->printer->cut();
        $this->printer->pulse();

        $this->printer->close();
    }

    public function printDuplicate(): Void
    {
        // implment logic to print duplicate bill
        $this->print();
    }

    public function getSeperator(): string
    {
        //45
        return "---------------------------------------------\n";
    }
}

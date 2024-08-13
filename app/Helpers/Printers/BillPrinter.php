<?php

namespace App\Helpers\Printers;

use App\Helpers\RestaurantHelper;
use Mike42\Escpos\Printer;
use App\Helpers\Printers\OrderItem;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\CapabilityProfile;

class BillPrinter
{
    private $billDetails;
    private $restaurentName;
    private $restaurentAddress;
    private $restaurentPhone;
    private $GST;
    private $email;
    private $website;
    private $tagLine;
    private Printer $printer;
    private $orderDetails;
    private $billerName;

    public function __construct(Printer $printer, $billDetails, $orderDetails)
    {
        $restarurentDetais = RestaurantHelper::getCachedRestaurantDetails();
        $this->restaurentName = $restarurentDetais->name;
        $this->restaurentAddress = $restarurentDetais->address;
        $this->restaurentPhone = $restarurentDetais->phone;
        $this->GST = $restarurentDetais->GST;
        $this->email = $restarurentDetais->email;
        $this->website = $restarurentDetais->website;
        $this->tagLine = $restarurentDetais->tagline;
        $this->billDetails = $billDetails;
        $this->orderDetails = $orderDetails;
        $this->printer = $printer;
        $this->billerName = Auth::user()->name;
    }

    public function print()
    {
        // Header
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->setEmphasis(true);
        $this->printer->setTextSize(2, 2);
        $this->printer->text($this->restaurentName . "\n");
        $this->printer->feed();
        $this->printer->setTextSize(1, 1);
        $this->printer->setEmphasis(false);
        $this->printer->text($this->restaurentAddress . "\n");
        if ($this->GST != null) {
            $this->printer->text("GSTIN : {$this->GST}\n");
        }
        $this->printer->text("PH : {$this->restaurentPhone}\n");
        if ($this->email != null) {
            $this->printer->text("{$this->email}\n");
        }
        if ($this->website != null) {
            $this->printer->text("{$this->website}\n");
        }

        $this->printDash();

        $this->printer->setEmphasis(true);

        $this->printer->text("Date: {$this->billDetails->created_at->format('Y-m-d h:i A')}");

        if ($this->billDetails->table_id == null) {
            $this->printer->text("          Take Away\n");
        } else {
            $this->printer->text("       Dine In: {$this->billDetails->table->name}\n");
        }
        $this->printer->text("\n");

        $this->printer->text("Cashier : {$this->billerName}");
        $this->printer->text("          Bill No: {$this->billDetails->bill_id}\n");

        $this->printer->setEmphasis(false);

        $this->printDash();

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

        $this->printDash();


        $itemsCount = count($this->orderDetails);
        $currentIndex = 0;

        foreach ($this->orderDetails as $name => $details) {
            $item = new OrderItem($name, $details['quantity'], $details['price'], $details['total']);
            $this->printer->text($item);

            $currentIndex++;
            if ($currentIndex < $itemsCount) {
                $this->printer->feed();
            }
        }


        $this->printDash();

        $this->printer->text("Total Qty :{$this->orderDetails->count()}");
        $this->printer->text("               Sub Total {$this->billDetails->bill_amount}\n");

        $this->printDash();
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);

        $this->printer->setEmphasis(true);

        $this->printer->text("Grand Total  Rs {$this->billDetails->grand_total}    \n");

        $this->printer->setEmphasis(false);
        // -----------------------------------
        $this->printDash();

        $this->printer->setJustification(Printer::JUSTIFY_CENTER);

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

    public function printDash()
    {
        //45
        $seperator =  "---------------------------------------------\n";

        $this->printer->setJustification(Printer::JUSTIFY_LEFT);

        $this->printer->text($seperator);

        $this->printer->setJustification();
    }
}

<?php

namespace App\Helpers\Printers;

class OrderItem
{
    private $name;
    private $quantity;
    private $price;
    private $amount;

    public function __construct($name, $quantity, $price, $amount)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->amount = $amount;
    }

    public function __toString()
    {
        $nameCol = 20;
        $qtyCol = 5;
        $priceCol = 10;
        $amountCol = 10;

        $nameStr = str_pad($this->name, $nameCol);
        $qtyStr = str_pad($this->quantity, $qtyCol, ' ', STR_PAD_LEFT);
        $priceStr = str_pad($this->price, $priceCol, ' ', STR_PAD_LEFT);
        $amountStr = str_pad($this->amount, $amountCol, ' ', STR_PAD_LEFT);

        return "$nameStr $qtyStr $priceStr $amountStr\n";
    }
}

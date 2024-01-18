<?php
class KOTItem
{
    private $name;
    private $quantity;

    public function __construct($name, $quantity)
    {
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function __toString()
    {
        $nameCol = 20;
        $qtyCol = 5;

        $nameStr = str_pad($this->name, $nameCol);
        $qtyStr = str_pad($this->quantity, $qtyCol, ' ', STR_PAD_LEFT);

        return "$nameStr $qtyStr\n";
    }
}

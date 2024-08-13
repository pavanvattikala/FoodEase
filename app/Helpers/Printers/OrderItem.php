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
        $this->amount = number_format($amount, 2, '.', ',');
    }

    public function __toString()
    {

        $nameCol = 22;
        $spaceAfterName = 3;
        $qtyCol = 5;
        $priceCol = 5;
        $amountCol = 10;


        $splitted_names = $this->wrapText($this->name, $nameCol);

        // build the row
        $row = str_pad($splitted_names[0], $nameCol, ' ', STR_PAD_RIGHT);
        // Add 5 spaces after the name column
        $row .= str_repeat(' ', $spaceAfterName);
        $row .= str_pad($this->quantity, $qtyCol, ' ', STR_PAD_RIGHT);
        $row .= str_pad($this->price, $priceCol, ' ', STR_PAD_LEFT);
        $row .= str_pad($this->amount, $amountCol, ' ', STR_PAD_LEFT);

        $row .= "\n"; // Ensure there's a new line at the end

        // Add the remaining lines
        for ($i = 1; $i < count($splitted_names); $i++) {
            $row .= str_pad($splitted_names[$i], $nameCol, ' ', STR_PAD_RIGHT);
            $row .= "\n";
        }

        return $row;
    }



    private function wrapText($text, $maxLength)
    {
        $lines = [];
        $textLength = strlen($text);
        for ($i = 0; $i < $textLength; $i += $maxLength) {
            $lines[] = substr($text, $i, $maxLength);
        }
        return $lines;
    }
}

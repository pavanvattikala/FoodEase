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
        $nameCol = 15;
        $qtyCol = 5;
        $priceCol = 5;
        $amountCol = 10;

        $nameLines = $this->wrapText($this->name, $nameCol);
        $output = '';

        // Handle the first line with quantity, price, and amount
        if (!empty($nameLines)) {
            $firstLine = array_shift($nameLines);
            $nameStr = str_pad($firstLine, $nameCol);
            $qtyStr = str_pad($this->quantity, $qtyCol, ' ', STR_PAD_LEFT);
            $priceStr = str_pad($this->price, $priceCol, ' ', STR_PAD_LEFT);
            $amountStr = str_pad($this->amount, $amountCol, ' ', STR_PAD_LEFT);
            $output .= "$nameStr $qtyStr $priceStr $amountStr";
        }

        // Add the remaining name lines
        foreach ($nameLines as $line) {
            $nameStr = str_pad($line, $nameCol);
            $output .= "\n$nameStr";
        }

        $output =  $output . "\n";
        dd($output);
        return $output;
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

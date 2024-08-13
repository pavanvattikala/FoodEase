<?php

namespace App\Helpers\Printers;

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
        $nameCol = 35;
        $spaceAfterName = 5;
        $qtyCol = 5;

        $splitted_names = $this->wrapText($this->name, $nameCol);

        // build the row
        $row = str_pad($splitted_names[0], $nameCol, ' ', STR_PAD_RIGHT);
        // Add  spaces after the name column
        $row .= str_repeat(' ', $spaceAfterName);
        $row .= str_pad($this->quantity, $qtyCol, ' ', STR_PAD_RIGHT);


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

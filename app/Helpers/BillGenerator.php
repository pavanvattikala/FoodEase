<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class BillGenerator
{
    const LINE_WIDTH = 32; // Assuming 80mm width (adjust as needed)
    const MAX_ITEM_NAME_LENGTH = 15;
    public static $limitedName = '';
    public static $remainingName = '';

    public static function generateThermalPrint($restaurant, $billDetails, $orderDetails, $billFullId)
    {
        $textContent = self::formatRestaurantInfo($restaurant) . "\n";
        $textContent .= self::formatBillInfo($billDetails) . "\n";
        $textContent .= self::formatOrderDetails($orderDetails, $billDetails, $restaurant);

        // Save to a text file
        $filePath = 'bills/bill_' . $billFullId . '.txt';
        Storage::put($filePath, $textContent);

        return $filePath;
    }

    private static function formatRestaurantInfo($restaurant)
    {
        return self::padText("{$restaurant['name']}", self::LINE_WIDTH, 'center') . "\n" .
            self::padText("{$restaurant['address']}", self::LINE_WIDTH, 'center') . "\n" .
            self::padText("Phone: {$restaurant['phone']}", self::LINE_WIDTH, 'center') . "\n" .
            self::padText("Tagline: {$restaurant['tagline']}", self::LINE_WIDTH, 'center') . "\n";
    }

    private static function formatBillInfo($billDetails)
    {
        return self::padText("Bill ID: {$billDetails['id']}", self::LINE_WIDTH, 'center') . "\n" .
            self::padText("Table: {$billDetails['table_no']}", self::LINE_WIDTH, 'center') . "\n" .
            self::padText("Date: {$billDetails['date']}", self::LINE_WIDTH, 'center') . "\n";
    }

    private static function formatOrderDetails($orderDetails, $billDetails, $restaurant)
    {
        $textContent = self::padText("Item", 15, 'right') .
            self::padText("Qty", 6, 'left') .
            self::padText("Price", 10, 'left') . "\n" .
            str_repeat('-', self::LINE_WIDTH) . "\n";

        foreach ($orderDetails as $itemName => $details) {
            $isNameFormatted = self::limitAndFormatItemName($itemName);
            $formattedItemName = self::$limitedName;

            $itemPadding = self::padText($formattedItemName, 16, 'right');
            $quantityPadding = self::padText($details['quantity'], 6, 'left');
            $pricePadding = self::padText($details['price'], 10, 'left');

            $textContent .= "{$itemPadding}{$quantityPadding}{$pricePadding}\n";
            if ($isNameFormatted) {
                $textContent .= self::$remainingName . "\n";
                self::$remainingName = '';
            }
        }

        $totalPadding = self::padText("Total: Rs {$billDetails['grand_total']}", self::LINE_WIDTH, 'left');
        $discountPadding = self::padText("Discount: Rs {$billDetails['discount']}", self::LINE_WIDTH, 'left');
        $grandTotalPadding = self::padText("Grand Total: Rs {$billDetails['grand_total']}", self::LINE_WIDTH, 'left');

        $textContent .= str_repeat('-', self::LINE_WIDTH) . "\n";
        $textContent .= "$totalPadding\n$discountPadding\n$grandTotalPadding\n\n";

        $textContent .= self::padText("**{$restaurant['tagline']}**", self::LINE_WIDTH, 'center') . "\n";
        $textContent .= self::padText("**Thank You For Dining With Us**", self::LINE_WIDTH, 'center') . "\n";

        return $textContent;
    }


    private static function padText($text, $width, $position)
    {
        $padding = $width - strlen($text);

        switch ($position) {
            case 'left':
                return str_pad($text, $width, ' ', STR_PAD_LEFT);
            case 'right':
                return str_pad($text, $width, ' ', STR_PAD_RIGHT);
            default:
                return str_pad($text, $width, ' ', STR_PAD_BOTH);
        }
    }

    private static function limitAndFormatItemName($itemName)
    {
        // Limit item name to at most 15 characters
        self::$limitedName = substr($itemName, 0, self::MAX_ITEM_NAME_LENGTH);
        $isnameLimited = false;

        // Add line break if the original name exceeds the limit
        if (strlen($itemName) > self::MAX_ITEM_NAME_LENGTH) {
            $isnameLimited = true;
            self::$remainingName .= trim(substr($itemName, self::MAX_ITEM_NAME_LENGTH));
        }

        return $isnameLimited;
    }
}

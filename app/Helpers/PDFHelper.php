<?php

namespace App\Helpers;

use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class PDFHelper
{

    public static function saveBillToDisk($id)
    {

        $bill = Bill::where('id', $id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();


        $billFullId = $bill->bill_id;

        $billDetails = collect([
            'id' => $billFullId,
            'table_no' => $bill->table_id,
            'grand_total' => $bill->grand_total,
            'discount' => $bill->discount,
            "date" => $bill->created_at->format('d M, Y h:i a')
        ]);

        $orderDetails = collect([]);

        foreach ($bill->orders as $order) {
            foreach ($order->orderDetails as $orderDetail) {
                $itemName = $orderDetail->menu->name;
                $quantity = $orderDetail->quantity;
                $price = $orderDetail->menu->price;


                // if key alredy exists it wil increment
                $orderDetails->put($itemName, ['quantity' => $quantity, 'price' => $price + 5]);
            }
        }

        $restaurantDetails = RestaurantHelper::getCachedRestaurantDetails();


        $restaurant = [
            "name" => $restaurantDetails->name,
            "address" => $restaurantDetails->address,
            "phone" => $restaurantDetails->phone,
            "tagline" => $restaurantDetails->tagline,
        ];


        $fileName = 'bill_' . $billFullId . '.pdf';

        $html = view('admin.bills.print', compact('billDetails', 'orderDetails', 'restaurant'))->render();

        //option array

        $width = 226.77; // 8cm
        $height = 25;

        $customPaper = array(0, 0, $width, $height);

        $pdf = Pdf::loadHTML($html)->setPaper($customPaper);

        $pdf->render();

        $canvas = $pdf->getCanvas();

        // Get the page count of the rendered PDF
        $page_count = $canvas->get_page_number();

        // dd($page_count);


        unset($pdf);

        //new pdf 

        $newHeight = ($height * $page_count);

        $dompdf = pdf::loadHTML($html);
        $dompdf->set_paper(array(0, 0, $width, $newHeight));

        // Save the PDF to storage
        $filePath = 'bills/' . $fileName;
        Storage::put($filePath, $dompdf->output());

        return $filePath;
    }

    public static function printBill($billPath)
    {
        $pdfPath = Storage::path($billPath);

        $printer = new Printer(config('predefined_options.printer.pos'));

        $printer->printToNetworkPrinter($pdfPath);
    }
}

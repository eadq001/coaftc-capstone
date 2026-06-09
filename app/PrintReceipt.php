<?php

namespace App;

use App\Models\Product;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintReceipt
{
    public static function print($transactionInfo, $reprint = false): void
    {
        $copies = ["Client's copy", "Guard's copy", 'COAFTC copy'];
//        $copies = ['COAFTC copy'];

        foreach ($copies as $copy) {

            // $connector = new WindowsPrintConnector('\\ASHLEYGWEN\\POS58');
            $connector = new WindowsPrintConnector('POS58');

            $printer = new Printer($connector);

            $printer->setTextSize(1, 1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("COAFTC\n");
            $printer->text("Sian, Sta. Cruz, Bislig City\n");
            $printer->feed();

            $printer->text("Associate: {$transactionInfo['cashier']}\n");
            $printer->text("PRF Number: {$transactionInfo['prfNumber']}\n");
            $printer->text("Date: {$transactionInfo['date']}\n");
            $printer->text("  Time: {$transactionInfo['time']}");

            $printer->feed(2);

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            //        $printer->text("Description      Qty      Amount\n");
            foreach ($transactionInfo['salesItems'] as $salesItem) {

                $product = Product::find($salesItem['product_id']);
                $productName = $product->name;
                $productUnit = $product->unit->unit_name;

                $printer->text($productName.' ');
                $printer->text($salesItem['quantity'].' '.$productUnit."\n");
            }
            $printer->feed();

            $printer->text('                    '.$copy . "\n");
            $date = \Illuminate\Support\now()->format('m/d/Y h:i:s A');

            if ($reprint) {
            $printer->text('         '.$date);
            }

            $printer->feed(2);

            $printer->text('-------------------------------');
            //        $printer->text("Total Amount: {$transactionInfo['grandTotal']}");

            $printer->feed(2);

            $printer->cut();
            $printer->close();
        }
    }
}

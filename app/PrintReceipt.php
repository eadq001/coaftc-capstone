<?php

namespace App;

use App\Models\Product;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintReceipt
{

    public static function print($transactionInfo): void
    {
//        dd($transactionInfo);
        $connector = new WindowsPrintConnector("POS58");

        $printer = new Printer($connector);

        $printer->setTextSize(1, 1);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("COAFTC BISLIG\n");
        $printer->text("Bislig, Surigao del Sur\n");
        $printer->feed();

        $printer->text("Cashier: {$transactionInfo['cashier']}\n");
        $printer->text("Prf Number: {$transactionInfo['prfNumber']}\n");
        $printer->text("Date: {$transactionInfo['date']}\n");
        $printer->text("  Time: {$transactionInfo['time']}");

        $printer->feed(2);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
//        $printer->text("Description      Qty      Amount\n");
        foreach ($transactionInfo['salesItems'] as $salesItem) {

            $product = Product::find($salesItem['product_id']);
            $productName = $product->name;
            $productUnit = $product->unit->unit_name;
//            dd($productName, $productUnit);

            $printer->text($productName . "\n");
            $printer->text($salesItem['quantity'] . " " . $productUnit. " X " . $salesItem['unit_price'] . "           ");
            $printer->text($salesItem['subtotal'] . "\n");
        }

        $printer->feed(2);

        $printer->text("Total Amount: {$transactionInfo['grandTotal']}");

        $printer->feed(4);

        $printer->cut();
        $printer->close();
    }
}
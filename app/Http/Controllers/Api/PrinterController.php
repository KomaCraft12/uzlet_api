<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Log;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PrinterController extends Controller
{

    public function generateLabel($id)
    {

        // termék adatai

        $product = Product::find($id);
        $productController = new ProductController();
        // response()->json($product);
        $product_raw = $productController->getByBarcode($product->barcode);
        $product =  $product_raw->original;

        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($product->barcode, BarcodeGeneratorPNG::TYPE_EAN_13);

        $data = [
            'barcode' => $product->barcode,
            'product' => $product->name,
            'description' => '',
            'unitPrice' => round($product->unit_price),
            'unit' => $product->unit,
            'price' => round($product->price),
            'isdiscounted' => $product->isdiscounted,
            'discounted' => $product->discounted,
            'valid_from' => '2023.06.27',
            'valid_to' => '2023.08.07',
            'barcodeImage' => "data:image/png;base64," . base64_encode($barcode),
            //'style' => asset('output.css'), // Linkeld be a generált CSS fájlt
        ];

        //return response()->json($data);

        // PDF generálása
        // $pdf = PDF::loadView('pricelabel', $data);

        if($data['isdiscounted']){
            $pdf = SnappyPdf::loadView('pricelabel_discounted', $data);
        } else {
            $pdf = SnappyPdf::loadView('pricelabel', $data);
        }

        //$pdf = SnappyPdf::loadView('pricelabel_discounted', $data);
        //$pdf = SnappyPdf::loadView('pricelabel', $data);

        // PDF fájl mentése
        $pdfFilePath = public_path('storage/label.pdf'); // Figyelj a pontos / vagy \ használatra
        file_put_contents($pdfFilePath, $pdf->output());

        // Nyomtatás
        $this->sendToPrinter($pdfFilePath);

        return response()->json(['message' => 'A címke nyomtatásra került!']);
    }

    public function printReceipt(Request $request)
    {
        $data = $request->all();

        // PDF fájl mentése
        $pdf = SnappyPdf::loadView('receipt', $data);
        $pdfFilePath = public_path('storage/receipt.pdf'); // Figyelj a pontos / vagy \ használatra
        file_put_contents($pdfFilePath, $pdf->output());

        // Nyomtatás
        //$this->sendToPrinter($pdfFilePath);

        return response()->json(['message' => 'A faktuра nyomtatásra került!']);
    }

    private function sendToPrinter($filePath)
    {
        $sumatraPath = public_path('SumatraPDF.exe');

        // A nyomtató neve
        $printerName = "HP Deskjet 1220C";

        // Parancs létrehozása
        $command = "\"$sumatraPath\" -print-to \"$printerName\" \"$filePath\"";

        // Nyomtatás parancs végrehajtása
        exec($command, $output, $return_var);

        Log::info('Nyomtatás parancs: ' . $command);
    }


    public function printDocument(Request $request)
    {
        $data = $request->all();

        $file = $request->file('file');

        if (!$file) {
            return response()->json(['message' => 'A fájl hiányzik!'], 400);
        }

        // A nyomtatási beállítások (pl. papírméret, szín)
        $settings = isset($data['settings']) ? json_decode($data['settings'], true) : [];

        // Fájl ideiglenes mappába mentése
        $tempFilePath = $file->storeAs('temp', $file->getClientOriginalName(), 'local'); // storage/app/temp/

        // Az elérési út módosítása a rendszer specifikus útvonalhoz
        $tempFilePath = str_replace('temp', 'private/temp', $tempFilePath);

        // Teljes fájl elérési útvonala
        $fullPath = storage_path('app/' . $tempFilePath);

        // Nyomtatás
        $this->sendToPrinter($fullPath, $settings);

        // Nyomtatás utáni fájl törlés (opcionálisan)
        unlink($fullPath);  // Fájl törlése, hogy ne maradjon ideiglenesen tárolva

        return response()->json(['message' => 'A dokumentum nyomtatásra került!']);
    }



}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StockEntryController;
use App\Http\Controllers\Api\PrinterController;
use App\Http\Controllers\Api\CouponController;

use Picqer\Barcode\BarcodeGeneratorPNG;

use App\Http\Middleware\CorsMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([CorsMiddleware::class])
    ->group(function () {

        Route::apiResource('products', ProductController::class);
        Route::post('products/{id}/stock', [ProductController::class, 'addStock']);
        // barcode route
        Route::get('products/barcode/{barcode}', [ProductController::class, 'getByBarcode']);
        Route::apiResource('discounts', DiscountController::class);
        
        Route::apiResource('stock-entries', StockEntryController::class);

        Route::apiResource('categories', CategoryController::class);

        // Route a címke generálására
        Route::get('products/{id}/generate-label/', [PrinterController::class, 'generateLabel']);

        // view template blade
        Route::get('/label-view', function () {

            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode('3856028500346', BarcodeGeneratorPNG::TYPE_EAN_13);
            // Adatok, amiket a view-nak átadunk
            $data = [
                'barcode' => '3856028500346',
                'product' => 'LEGO FRIENDS 41716 STEPHANIE',
                'description' => 'Vitorlás kalandja',
                'price' => '12.943',
                'unitPrice' => '12.943',
                'unit' => 'db',
                'valid_from' => '2023.06.27',
                'valid_to' => '2023.08.07',
                'barcodeImage' => "data:image/png;base64,".base64_encode($barcode),
                //'style' => asset('output.css'), // Linkeld be a generált CSS fájlt
            ];
        
            // A 'label' nevű Blade fájl megjelenítése
            return view('pricelabel', $data);
        });

        Route::get('/receipt-view', function () {
            
            $products = [
                ['name' => 'Milka Waffelini Milk 31g', 'quantity' => 1, 'unit_price' => 200, 'discount_price' => 190, 'total_price' => 190],
                ['name' => 'Twix kekszes szelet', 'quantity' => 1, 'unit_price' => 350, 'discount_price' => null, 'total_price' => 350],
                ['name' => 'Egri bikavér', 'quantity' => 1, 'unit_price' => 1200, 'discount_price' => 960, 'total_price' => 960],
                ['name' => 'ISY Micro batteries', 'quantity' => 1, 'unit_price' => 4500, 'discount_price' => 2700, 'total_price' => 2700],
                ['name' => 'Milky Way szelet', 'quantity' => 3, 'unit_price' => 200, 'discount_price' => null, 'total_price' => 600],
            ];
            $total_price = array_sum(array_column($products, 'total_price'));

            // Adatok, amiket a view-nak átadunk
            $data = [
                'products' => $products,
                'total_price' => $total_price,
                'payment_method' => 'Készpénz',
                'cashier' => 'Kasszás',
            ];

            // A 'receipt' nevű Blade fájl megjelenítése
            return view('receipt', $data);


        });

        Route::post('/print-receipt', [PrinterController::class, 'printReceipt']);

        Route::post('/print-document', [PrinterController::class, 'printDocument']);

        Route::prefix('coupons')->group(function () {
            Route::post('/', [CouponController::class, 'store']); // Létrehozás
            Route::get('/', [CouponController::class, 'index']); // Listázás
            Route::get('{id}', [CouponController::class, 'show']); // Kupon megtekintése
            Route::put('{id}', [CouponController::class, 'update']); // Frissítés
            Route::delete('{id}', [CouponController::class, 'destroy']); // Törlés
            Route::post('/apply', [CouponController::class, 'applyCoupon']);
            Route::get('/check/{code}', [CouponController::class, 'checkCoupon']);
        });


    });
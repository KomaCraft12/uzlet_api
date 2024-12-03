<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Discount;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        foreach ($products as $product) {
            $discount = Discount::select("*")->where('product_id', $product->id)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

            $discounted_price = $discount ? $product->price - $product->price * ($discount->discount_amount / 100) : $product->price;

            $product->isdiscounted = !is_null($discount);
            $product->discounted = $discount ? [
                'price' => round($discounted_price,2),
                "amount" => $discount->discount_amount,
                'start' => $discount->start_date,
                'stop' => $discount->end_date
            ] : [];
        }

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Lekérdezzük az aktív kedvezményeket
        $discount = Discount::select("*")->where('product_id', $id)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        $discounted_price = $discount ? $product->price - $product->price * ($discount->discount_amount / 100) : $product->price;

        $product->isdiscounted = !is_null($discount);
        $product->discounted = $discount ? [
            'price' => round($discounted_price,2),
            "amount" => $discount->discount_amount,
            'start' => $discount->start_date,
            'stop' => $discount->end_date
        ] : [];

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    public function addStock(Request $request, $id)
    {
        $stockEntry = StockEntry::create([
            'product_id' => $id,
            'quantity' => $request->quantity
        ]);

        return response()->json($stockEntry, 201);
    }
}

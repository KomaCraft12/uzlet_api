<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockEntry;
use App\Models\Product;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    // Készletbejegyzés hozzáadása
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric',
        ]);

        // StockEntry létrehozása
        $stockEntry = StockEntry::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->quantity); 

        return response()->json($stockEntry, 201);
    }

    // Készletbejegyzések listázása
    public function index()
    {
        $stockEntries = StockEntry::with('product')->latest()->get();
        return response()->json($stockEntries);
    }

    // Készletbejegyzés részletezése
    public function show($id)
    {
        $stockEntry = StockEntry::with('product')->findOrFail($id);
        return response()->json($stockEntry);
    }

    // Készletbejegyzés törlése
    public function destroy($id)
    {
        $stockEntry = StockEntry::findOrFail($id);
        $stockEntry->delete();
        return response()->json(['message' => 'Stock entry deleted successfully']);
    }
}


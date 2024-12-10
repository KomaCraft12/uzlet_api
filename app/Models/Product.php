<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'barcode', 'price', 'category_id', 'quantity', 'unit', 'unit_price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    public function getStockAttribute()
    {
        return $this->stockEntries->sum('quantity');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    protected static function booted()
    {
        static::saving(function ($product) {
            if ($product->quantity > 0) {
                switch ($product->unit) {
                    case 'g': // Grammban megadott mennyiség
                        $convertedQuantity = $product->quantity; // Átváltás kilogrammra
                        break;
                    case 'kg': // Kilogrammban megadott mennyiség
                        $convertedQuantity = $product->quantity / 1000;
                        break;
                    case 'ml': // Milliliterben megadott mennyiség
                        $convertedQuantity = $product->quantity; // Átváltás literre
                        break;
                    case 'liter': // Literben megadott mennyiség
                        $convertedQuantity = $product->quantity / 1000;
                        break;
                    default: // Egyéb esetek, ahol nincs átváltás szükséges
                        $convertedQuantity = $product->quantity;
                        break;
                }

                $product->quantity = $convertedQuantity;
        
                // Egységár kiszámítása az átváltott mennyiség alapján
                if ($convertedQuantity > 0) {
                    $product->unit_price = round($product->price / $convertedQuantity,2);
                } else {
                    $product->unit_price = null;
                }
            } else {
                $product->unit_price = null; // Null, ha nincs mennyiség
            }
        });
        
    }

}
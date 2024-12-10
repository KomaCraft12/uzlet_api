<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active'
    ];

    // A kuponokhoz tartozó felhasználások kapcsolata
    public function uses()
    {
        return $this->hasMany(CouponUse::class);
    }

    // Esetleg kupon érvényességi idő ellenőrzése
    public function isValid()
    {
        $now = now();
        return $this->is_active && $this->start_date <= $now && $this->end_date >= $now && $this->used_count < $this->usage_limit;
    }
}

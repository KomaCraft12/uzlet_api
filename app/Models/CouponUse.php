<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUse extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'used_at'
    ];

    // A CouponUse model kapcsolata a Coupon modelhez
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Esetleg egy metódus a felhasználás idejének beállításához
    public function setUsedAtNow()
    {
        $this->used_at = now();
        $this->save();
    }
}

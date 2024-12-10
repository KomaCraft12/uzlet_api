<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use App\Models\CouponUse;

class CouponController extends Controller
{
    // Kupon létrehozása
    public function store(Request $request)
    {
        // Validálás
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'required|integer|min:1',
        ]);

        // Új kupon mentése
        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Kupon sikeresen létrehozva.',
            'coupon' => $coupon
        ], 201);
    }

    // Kupon adatainak lekérdezése
    public function show($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Kupon nem található.'], 404);
        }

        return response()->json($coupon);
    }

    // Kuponok listázása
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json($coupons);
    }

    // Kupon frissítése
    public function update(Request $request, $id)
    {
        // Lekérjük a kupon rekordot
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Kupon nem található.'], 404);
        }

        // Validálás
        $validated = $request->validate([
            'code' => 'sometimes|required|string|max:255|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'sometimes|required|in:percent,amount',
            'discount_value' => 'sometimes|required|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'usage_limit' => 'sometimes|required|integer|min:1',
        ]);

        // Frissítés
        $coupon->update($validated);

        return response()->json([
            'message' => 'Kupon sikeresen frissítve.',
            'coupon' => $coupon
        ]);
    }

    // Kupon törlése
    public function destroy($id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Kupon nem található.'], 404);
        }

        $coupon->delete();

        return response()->json(['message' => 'Kupon sikeresen törölve.']);
    }

    public function checkCoupon($code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Kupon nem található.'], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json(['message' => 'A kupon érvénytelen vagy lejárt.'], 400);
        }

        return response()->json($coupon);
    }

    // Kupon ellenőrzése és alkalmazása
    public function applyCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');
        $coupon = Coupon::where('code', $couponCode)->first();

        if ($coupon && $coupon->isValid()) {
            // Kupon alkalmazása
            $couponUse = new CouponUse([
                'coupon_id' => $coupon->id,
                'order_id' => null,
                'used_at' => now(),
            ]);
            $couponUse->save();

            // A kupon felhasználása után frissítjük a használt darabszámot
            $coupon->increment('used_count');

            return response()->json(['message' => 'Kupon sikeresen alkalmazva!']);
        }

        return response()->json(['message' => 'A kupon érvénytelen vagy lejárt.'], 400);
    }
}

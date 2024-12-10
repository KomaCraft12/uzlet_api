<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('coupon_uses', function (Blueprint $table) {
            $table->id(); // Automatikus azonosító
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade'); // Kapcsolat a kuponokkal
            $table->unsignedBigInteger('order_id')->nullable(); // Opcionális felhasználó azonosító
            $table->dateTime('used_at'); // Kupon felhasználásának ideje
            $table->timestamps(); // Létrehozva és frissítve mezők
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('coupon_uses');
    }
};

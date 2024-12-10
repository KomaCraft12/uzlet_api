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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id(); // Automatikus azonosító
            $table->string('code')->unique(); // Egyedi kuponkód (pl. vonalkód)
            $table->enum('discount_type', ['percentage', 'fixed']); // Kedvezmény típusa
            $table->decimal('discount_value', 10, 2); // Kedvezmény értéke (pl. 10% vagy 500 HUF)
            $table->dateTime('start_date')->nullable(); // Érvényesség kezdete
            $table->dateTime('end_date')->nullable(); // Érvényesség vége
            $table->integer('usage_limit')->nullable(); // Max. felhasználások száma
            $table->integer('used_count')->default(0); // Jelenlegi felhasználási szám
            $table->boolean('is_active')->default(true); // Aktív-e a kupon
            $table->timestamps(); // Létrehozva és frissítve mezők
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};

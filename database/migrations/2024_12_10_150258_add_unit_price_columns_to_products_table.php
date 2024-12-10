<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('quantity')->nullable()->after('price'); // Kiszerelés mennyisége
            $table->string('unit')->nullable()->after('quantity'); // Kiszerelés típusa
            $table->float('unit_price')->nullable()->after('unit'); // Egységár
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'unit', 'unit_price']);
        });
    }
};

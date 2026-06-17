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
            $table->decimal('stock_level', 10, 2)->change();
        });

        Schema::table('sales_items', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change();
            $table->decimal('inventory_start', 10, 2)->change();
            $table->decimal('inventory_end', 10, 2)->change();
        });

        Schema::table('dispersal_items', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change();
            $table->decimal('inventory_start', 10, 2)->change();
            $table->decimal('inventory_end', 10, 2)->change();
        });

        Schema::table('stock_additions', function (Blueprint $table) {
            $table->decimal('quantity_added', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_level')->change();
        });

        Schema::table('sales_items', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->integer('inventory_start')->change();
            $table->integer('inventory_end')->change();
        });

        Schema::table('dispersal_items', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->integer('inventory_start')->change();
            $table->integer('inventory_end')->change();
        });

        Schema::table('stock_additions', function (Blueprint $table) {
            $table->integer('quantity_added')->change();
        });
    }
};

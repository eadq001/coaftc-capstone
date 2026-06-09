<?php

use App\Models\Dispersal;
use App\Models\Product;
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
        Schema::create('dispersal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dispersal::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->restrictOnDelete();
            $table->integer('quantity');
            $table->string('class')->nullable();
            $table->integer('inventory_start');
            $table->integer('inventory_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispersal_items');
    }
};

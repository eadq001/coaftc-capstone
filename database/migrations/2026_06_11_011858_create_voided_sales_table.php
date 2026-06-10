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
        Schema::create('voided_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_sale_id')->nullable();
            $table->string('prf_number');
            $table->integer('original_total_amount');
            $table->integer('modified_total_amount')->nullable();
            $table->string('action');
            $table->foreignId('authorized_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('original_cashier_id')->constrained('users')->restrictOnDelete();
            $table->json('original_items');
            $table->json('modified_items')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('voided_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voided_sales');
    }
};

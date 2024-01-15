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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('food_supplier')->nullable();
            $table->string('categoryfood')->nullable();
            $table->string('unit')->nullable();
            //$table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            //$table->integer('product_price');
            $table->decimal('have',9,1)->nullable();
            $table->decimal('product_amount',9,1)->nullable();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

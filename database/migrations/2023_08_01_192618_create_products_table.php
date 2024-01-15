<?php

use App\Models\Supplier;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->string('supplier')->nullable();
            $table->string('categoryfood')->nullable();
            //$table->foreignId('supplier_id')->nullable();
            //$table->foreignId('restaurant_id')->nullable()->constrained();
            //$table->foreignId('user_id')->nullable()->constrained();
            //$table->foreignId('categoryfood_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

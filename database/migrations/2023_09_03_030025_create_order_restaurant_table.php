<?php

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_restaurant', function (Blueprint $table) {
            $table->id();
            //$table->foreignIdFor(Restaurant::class);
            //$table->foreignIdFor(Order::class);
            $table->foreignIdFor(Restaurant::class)->onDelete('cascade');
            $table->foreignIdFor(Order::class)->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_restaurant');
    }
};

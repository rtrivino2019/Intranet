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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->longText('body');
            $table->datetime('report_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->decimal('cash',9,2)->nullable();
            $table->decimal('credit_card',9,2)->nullable();
            $table->decimal('online',9,2)->nullable();
            $table->decimal('doordash',9,2)->nullable();
            $table->decimal('uber',9,2)->nullable();
            $table->decimal('grubhub',9,2)->nullable();
            $table->decimal('sales',9,2)->nullable();

            $table->decimal('food',9,2)->nullable();
            $table->decimal('liquor',9,2)->nullable();
            $table->decimal('beer',9,2)->nullable();
            $table->decimal('wine',9,2)->nullable();
            $table->decimal('taxes',9,2)->nullable();
            $table->decimal('discount',9,2)->nullable();
            $table->decimal('voids',9,2)->nullable();
            $table->decimal('gift_certificate',9,2)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

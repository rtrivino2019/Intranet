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
        Schema::create('percentages', function (Blueprint $table) {
            $table->id();
            $table->datetime('expense_date')->nullable();
            $table->string('check')->nullable();
            $table->string('concept')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount',9,2)->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('supplier_id')->nullable()->constrained();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percentages');
    }
};

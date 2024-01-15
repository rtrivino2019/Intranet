<?php

use App\Models\Report;
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
        Schema::create('report_restaurant', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Restaurant::class);
            $table->foreignIdFor(Report::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_restaurant');
    }
};

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
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->string('rainfall');
            $table->string('temperature_min');
            $table->string('temperature_max');
            $table->string('temperature_mean');
            $table->string('wind_speed');
            $table->string('wind_direction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};

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
     Schema::create('business_hours', function (Blueprint $table) {
    $table->id();
    $table->string('day'); // Monday, Tuesday, etc.
    $table->time('open_time')->nullable();
    $table->time('close_time')->nullable();
    $table->boolean('is_closed')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buisness_hours');
    }
};

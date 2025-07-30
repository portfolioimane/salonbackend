<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('payment_method'); // Add payment method column
            $table->decimal('total', 10, 2); // Add total amount column
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('pending'); // Order status            $table->timestamps();
            $table->decimal('paid_amount', 10, 2)->default(0); // Add paid_amount column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique(); // যেমন: BK-XY12AB
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->string('passenger_phone');

            // Trip Info
            $table->string('trip_type'); // fromAirport, toAirport, point-to-point
            $table->text('pickup_address');
            $table->text('dropoff_address');
            $table->date('pickup_date');
            $table->string('pickup_time'); // String রাখা নিরাপদ (যেমন: 10:30 AM)
            $table->decimal('distance', 8, 2)->default(0); // যেমন: 12.50 miles
            $table->string('vehicle_type')->nullable();

            // Airport Info (Nullable কারণ সব ট্রিপ এয়ারপোর্ট না)
            $table->string('airline_name')->nullable();
            $table->string('flight_number')->nullable();

            // Payment Info
            $table->decimal('total_fare', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);

            $table->string('payment_method')->nullable(); // cash, card, deposit
            $table->string('payment_status')->default('pending'); // pending, partial, paid
            $table->string('transaction_id')->nullable(); // Square Transaction ID

            // General Status
            $table->string('status')->default('confirmed'); // pending, confirmed, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

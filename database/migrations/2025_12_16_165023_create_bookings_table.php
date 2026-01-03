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
            $table->string('booking_no')->unique(); // BLAT-0001

            // --- Passenger Info ---
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->string('passenger_phone');
            $table->string('phone_country_code')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->text('mailing_address')->nullable();
            $table->text('special_needs')->nullable();

            // --- Trip Details ---
            $table->string('trip_type');
            $table->date('pickup_date');
            $table->string('pickup_time');
            $table->text('pickup_address');
            $table->text('dropoff_address');
            $table->decimal('distance_miles', 8, 2)->default(0);

            // --- Flight Info ---
            $table->string('airline_name')->nullable();
            $table->string('flight_number')->nullable();

            // --- Vehicle Info ---
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->integer('vehicles_used')->default(1);

            // --- Pax & Luggage ---
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->integer('total_passengers')->default(0);
            $table->integer('luggage')->default(0);

            // --- Extras Counts ---
            $table->integer('booster_seat_count')->default(0);
            $table->integer('infant_seat_count')->default(0);
            $table->integer('front_seat_count')->default(0);
            $table->integer('stopover_count')->default(0);
            $table->integer('pet_count')->default(0);

            // --- Billing Info ---
            $table->string('card_holder_name')->nullable();
            $table->string('billing_phone')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();

            // --- Fare Breakdown ---
            $table->decimal('estimated_fare', 10, 2)->default(0);
            $table->decimal('gratuity', 10, 2)->default(0);
            $table->decimal('pickup_tax', 10, 2)->default(0);
            $table->decimal('dropoff_tax', 10, 2)->default(0);
            $table->decimal('parking_fee', 10, 2)->default(0);
            $table->decimal('toll_fee', 10, 2)->default(0);
            $table->decimal('surcharge_fee', 10, 2)->default(0);
            $table->decimal('extra_luggage_fee', 10, 2)->default(0);

            $table->decimal('child_seat_fee', 10, 2)->default(0);
            $table->decimal('booster_seat_fee', 10, 2)->default(0);
            $table->decimal('front_seat_fee', 10, 2)->default(0);
            $table->decimal('stopover_fee', 10, 2)->default(0);
            $table->decimal('extras_total', 10, 2)->default(0);

            // --- Payment Totals ---
            $table->decimal('total_fare', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);

            // --- Payment Meta ---
            $table->string('payment_method')->default('cash');
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();

            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();

            // --- JSON Columns (The Fix for your Error) ---
            $table->json('surcharge_details')->nullable();
            $table->json('extra_charge_details')->nullable();

            $table->string('status')->default('confirmed');
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

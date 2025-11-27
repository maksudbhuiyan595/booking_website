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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->decimal('base_fare', 10, 2);
            $table->decimal('min_fare', 10, 2);
            $table->json('slabs')->nullable();
            $table->text('features')->nullable();
            $table->integer('capacity_passenger');
            $table->integer('capacity_luggage');
            $table->string('image')->nullable();
            $table->boolean('has_extra_seat')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

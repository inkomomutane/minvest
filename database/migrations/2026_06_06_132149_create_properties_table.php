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
        Schema::create('properties', static function (Blueprint $table) {
            $table->ulid('id')->primary();

            # Basic Info
            $table->string('name');
            $table->longText('about')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable()->comment('e.g., "available", "sold", "pending"');
            $table->string('slug')->unique();

            # Details
            $table->unsignedInteger('bedrooms')->nullable();
            $table->unsignedInteger('beds')->nullable();
            $table->unsignedInteger('bathrooms')->nullable();

            $table->unsignedInteger('max_guests')->nullable();
            $table->unsignedInteger('max_kids_allowed')->nullable();
            $table->unsignedInteger('max_pets_allowed')->nullable();
            $table->unsignedInteger('max_vehicles_allowed')->nullable();
            $table->decimal('area_size', 18, 6)->nullable()->comment('Size of the property in square feet or meters');


            # Pricing.
            $table->decimal('base_price', 18, 6)->nullable()->comment('Base price per night');
            $table->decimal('cleaning_fee', 18, 6)->nullable()->comment('Cleaning fee');
            $table->decimal('service_fee', 18, 6)->nullable()->comment('Service fee');
            $table->decimal('taxes', 18, 6)->nullable()->comment('Taxes');
            $table->decimal('security_deposit', 18, 6)->nullable()->comment('Security deposit');
            $table->decimal('extra_guest_fee', 18, 6)->nullable()->comment('Fee per extra guest beyond max_guests');
            $table->decimal('pet_fee', 18, 6)->nullable()->comment('Fee per pet');
            $table->decimal('vehicle_fee', 18, 6)->nullable()->comment('Fee per vehicle');


            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('type')->references('name')->on('property_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};

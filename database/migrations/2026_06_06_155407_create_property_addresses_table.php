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
        Schema::create('property_addresses', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('property_id');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->longText('directions')->nullable();

            $table->longText('google_maps_embed_code')->nullable();
            $table->longText('google_map_link')->nullable();
            $table->longText('google_maps_place_id')->nullable();

            $table->timestamps();
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_addresses');
    }
};

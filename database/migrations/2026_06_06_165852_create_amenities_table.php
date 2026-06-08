<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amenity_categories', static function (Blueprint $table) {
            $table->string('name')->primary();
            $table->text('description')->nullable();

            $table->string('icon')->nullable();
            $table->foreign('icon')->references('name')->on('icons')->nullOnDelete();

        });
        Schema::create('amenities', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable()->comment('Category of the amenity, e.g., "general", "safety", "entertainment"');

            $table->string('icon')->nullable();
            $table->timestamps();
            $table->foreign('category')->references('name')->on('amenity_categories')->nullOnDelete();
            $table->foreign('icon')->references('name')->on('icons')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_categories');
        Schema::dropIfExists('amenities');
    }
};

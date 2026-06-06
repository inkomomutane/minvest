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

        Schema::create('property_rule_categories', static function (Blueprint $table) {
             $table->string('name')->primary()->comment('e.g., "house rules", "health and safety", "cancellation policy"');
             $table->text('description')->nullable();
             $table->string('icon')->nullable();
             $table->string('icon_type')->nullable();
        });


        Schema::create('property_rules', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('property_id');
            $table->string('category')->nullable();
            $table->string('name')->nullable();
            $table->longText('content');

            $table->string('icon')->nullable();
            $table->string('icon_type')->nullable();

            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();
            $table->foreign('category')->references('name')->on('property_rule_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_rule_categories');
        Schema::dropIfExists('property_rules');
    }
};

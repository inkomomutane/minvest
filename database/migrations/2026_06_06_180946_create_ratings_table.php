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
        Schema::create('ratings',static function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('property_id');
            $table->string('user_id');
            $table->string('user_name')->nullable();
            $table->unsignedTinyInteger('rating')->comment('Rating value from 1 to 5')->default(1);
            $table->longText('comment')->nullable();

            $table->dateTime('review_date')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};

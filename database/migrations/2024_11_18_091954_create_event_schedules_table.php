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
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_guid');
            $table->string('day_name');
            $table->date('event_date');
            $table->json('activity');
            $table->integer('target');
            $table->string('business_zone');
            $table->string('event_vanue');
            $table->timestamps();

            $table->foreign('event_guid')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};

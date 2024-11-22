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
        Schema::create('staff_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_guid');
            $table->string('group_name');
            $table->uuid('mentor');
            $table->uuid('leader');
            $table->string('vehicle');
            $table->timestamps();

            $table->foreign('event_guid')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('mentor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('leader')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_groups');
    }
};

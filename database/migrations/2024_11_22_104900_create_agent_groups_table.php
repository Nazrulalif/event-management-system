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
        Schema::create('agent_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_guid');
            $table->uuid('nazir');
            $table->timestamps();

            $table->foreign('event_guid')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('nazir')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_groups');
    }
};

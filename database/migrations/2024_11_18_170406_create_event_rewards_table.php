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
        Schema::create('event_rewards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_guid');
            $table->json('prize');  // JSON for storing prize values (first, second, third)
            $table->text('condition');  // Text for condition (could be longer content)
            $table->timestamps();

            $table->foreign('event_guid')->references('id')->on('events')->onDelete('cascade');

            // Optional: Add indexes for better querying performance
            $table->index('event_guid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rewards');
    }
};

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
        Schema::create('event_targets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_guid');
            $table->string('product')->nullable();
            $table->decimal('arpu', 10, 2)->nullable();
            $table->integer('sales_physical_target')->nullable();
            $table->integer('outbase')->nullable();
            $table->integer('inbase')->nullable();
            $table->decimal('revenue', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('event_guid')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_targets');
    }
};

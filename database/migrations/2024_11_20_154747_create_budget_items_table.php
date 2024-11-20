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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('budget_guid');
            $table->text('description');
            $table->integer('days')->default(0);
            $table->integer('frequency')->default(0);
            $table->decimal('price_per_unit', 15, 2)->default(0);
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->foreign('budget_guid')->references('id')->on('event_budgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};

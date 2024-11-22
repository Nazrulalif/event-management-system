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
        Schema::create('agent_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_guid');
            $table->uuid('agent_guid');
            $table->timestamps();

            $table->foreign('group_guid')->references('id')->on('agent_groups')->onDelete('cascade');
            $table->foreign('agent_guid')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_members');
    }
};

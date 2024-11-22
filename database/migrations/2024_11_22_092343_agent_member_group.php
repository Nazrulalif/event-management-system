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
        Schema::create('agent_member_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_guid');
            $table->uuid('agent_guid');
            $table->timestamps();

            $table->foreign('group_guid')->references('id')->on('staff_groups')->onDelete('cascade');
            $table->foreign('agent_guid')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_member_groups');
    }
};

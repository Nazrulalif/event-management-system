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
        Schema::create('staff_member_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_guid');
            $table->uuid('staff_guid');
            $table->timestamps();

            $table->foreign('group_guid')->references('id')->on('staff_groups')->onDelete('cascade');
            $table->foreign('staff_guid')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_member_groups');
    }
};

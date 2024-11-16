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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('unit')->default('Consumer');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_guid');
            $table->string('gender')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('state')->default('Melaka');
            $table->string('team')->nullable();
            $table->string('is_approve')->nullable();
            $table->string('is_active')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_guid')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

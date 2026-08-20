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
            $table->id();
            $table->string('empNo', 50)->nullable();
            $table->string('username')->unique();
            $table->string('comp_email')->unique();
            $table->string('password');
            $table->timestamp('password_expires_at')->nullable();
            $table->string('access_level')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('reg_otp')->nullable();
            $table->timestamp('reg_otp_expires_at')->nullable();
            $table->integer('is_locked')->default(0);
            $table->integer('cookies_validation')->nullable();
            $table->string('cookies_validation_count')->nullable();
            $table->string('session_id')->nullable();
            $table->boolean('first_login')->default(1);
            $table->text('avatar_url')->nullable();
            $table->text('app_authentication_secret')->nullable();
            $table->boolean('has_email_authentication')->default(false);
            $table->text('app_authentication_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

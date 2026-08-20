<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblOTP', function (Blueprint $table) {
            $table->string('EmpNo', 50)->primary();
            $table->string('OTP', 45)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblOTP');
    }
};

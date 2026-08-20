<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblEmpStatus', function (Blueprint $table) {
            $table->integer('EmpStatusNo')->primary();
            $table->string('EmpStatusDesc', 50)->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->dateTime('DateCreated')->nullable();
            $table->dateTime('DateUpdated')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblEmpStatus');
    }
};

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
        Schema::create('tblProject', function (Blueprint $table) {
            $table->string('ProjectNo', 150)->primary();
            $table->string('ProjectDesc', 500)->nullable();
            $table->integer('BUNo')->nullable();
            $table->string('CostCntrNo', 150)->nullable();
            $table->integer('ClientID')->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('DateCreated')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->dateTime('DateUpdated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblProject');
    }
};

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
        Schema::create('tblBusinessUnits', function (Blueprint $table) {
            $table->integer('BusinessUnitNo')->primary();
            $table->string('BusinessUnitDesc', 200)->nullable();
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
        Schema::dropIfExists('tblBusinessUnits');
    }
};

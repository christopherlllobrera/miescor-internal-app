<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblEmployee', function (Blueprint $table) {
            $table->string('EmpNo', 50)->primary();
            $table->string('EmpLName', 50)->nullable();
            $table->string('EmpFName', 50)->nullable();
            $table->string('EmpMName', 50)->nullable();
            $table->string('EmpAddress', 500)->nullable();
            $table->integer('PostNo')->nullable();
            $table->integer('CompNo')->nullable();
            $table->string('DeptNo', 50)->nullable();
            $table->string('LocNo', 50)->nullable();
            $table->string('EmpContact1', 25)->nullable();
            $table->string('EmpContact2', 25)->nullable();
            $table->string('EmpContact3', 25)->nullable();
            $table->string('EmpEmergency', 50)->nullable();
            $table->string('EmpEmerContact', 50)->nullable();
            $table->string('EmpEmailAd', 50)->nullable();
            $table->string('PictName', 50)->nullable();
            $table->longText('ItemPict')->binary()->nullable();
            $table->string('Gender', 50)->nullable();
            $table->string('TINNo', 50)->nullable();
            $table->string('SSSNo', 50)->nullable();
            $table->string('PAGIBIGNo', 50)->nullable();
            $table->string('PHILHEALTHNo', 50)->nullable();
            $table->integer('EmpStatusNo')->nullable();
            $table->integer('StatusNo')->nullable();
            $table->integer('CivilNo')->nullable();
            $table->string('MedCardNo', 150)->nullable();
            $table->string('MedCardPolicyNo', 150)->nullable();
            $table->dateTime('DateHired')->nullable();
            $table->dateTime('BirthDate')->nullable();
            $table->date('RegularizationDate')->nullable();
            $table->integer('CreatedBy')->nullable();
            $table->dateTime('DateCreated')->nullable();
            $table->integer('UpdatedBy')->nullable();
            $table->dateTime('DateUpdated')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblEmployee');
    }
};

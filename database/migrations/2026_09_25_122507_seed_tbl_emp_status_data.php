<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('tblEmpStatus')) {
            Schema::create('tblEmpStatus', function (Blueprint $table) {
                $table->integer('EmpStatusNo')->primary();
                $table->string('EmpStatusDesc', 50)->nullable();
                $table->integer('CreatedBy')->nullable();
                $table->dateTime('DateCreated')->nullable();
                $table->integer('UpdatedBy')->nullable();
                $table->dateTime('DateUpdated')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tblEmpStatus')->whereIn('EmpStatusNo', [1, 2, 3, 4])->delete();
    }
};

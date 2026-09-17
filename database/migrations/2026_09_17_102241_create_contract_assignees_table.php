<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contract_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->string('emp_no');
            $table->timestamps();
            
            // If the tblEmployee table allows it, we can add a foreign key:
            // $table->foreign('emp_no')->references('EmpNo')->on('tblEmployee')->cascadeOnDelete();
        });

        // Data Migration: move existing assigned_to data to the pivot table
        $contracts = DB::table('contracts')->whereNotNull('assigned_to')->get();
        foreach ($contracts as $contract) {
            $emp = DB::table('tblEmployee')->where('EmpLName', $contract->assigned_to)->first();
            if ($emp) {
                DB::table('contract_assignees')->insert([
                    'contract_id' => $contract->id,
                    'emp_no' => $emp->EmpNo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_assignees');
    }
};

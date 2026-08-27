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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('empNo')->nullable();
            $table->foreign('empNo')->references('EmpNo')->on('tblEmployee')->cascadeOnDelete();
            $table->string('employee_group')->nullable();
            $table->string('location_id')->nullable();
            $table->string('schedule')->nullable();
            $table->string('available_credits')->nullable();
            $table->string('vl_balance')->nullable();
            $table->string('sl_balance')->nullable();
            $table->string('type')->nullable();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('days_total')->nullable();
            $table->text('reason')->nullable();
            $table->string('attachment')->nullable();
            $table->string('duration')->nullable();
            $table->string('status')->default('Pending');
            $table->string('immediate_supervisor_id')->nullable();
            $table->foreign('immediate_supervisor_id')->references('EmpNo')->on('tblEmployee')->cascadeOnDelete();
            $table->string('next_level_supervisor_id')->nullable();
            $table->foreign('next_level_supervisor_id')->references('EmpNo')->on('tblEmployee')->cascadeOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};

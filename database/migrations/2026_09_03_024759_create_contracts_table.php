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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->text('contract_description');
            $table->string('assigned_to');
            $table->string('attachment')->nullable();
            $table->string('contract_type');
            $table->string('status')->default('pending');
            $table->unsignedInteger('turnaround_days')->default(3);
            $table->boolean('has_turnaround_time')->default(true);
            $table->flowforgePositionColumn('position');
            $table->timestamps();

            $table->unique(['status', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

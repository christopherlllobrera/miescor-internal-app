<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('contracts', 'assigned_to')) {
            Schema::table('contracts', function (Blueprint $table): void {
                $table->string('assigned_to')->nullable()->after('contract_description');
            });
        }

        if (! Schema::hasColumn('contracts', 'attachment')) {
            Schema::table('contracts', function (Blueprint $table): void {
                $table->string('attachment')->nullable()->after('assigned_to');
            });
        }

        if (! Schema::hasColumn('contracts', 'turnaround_days')) {
            Schema::table('contracts', function (Blueprint $table): void {
                $table->unsignedInteger('turnaround_days')->default(3)->after('status');
            });
        }

        if (! Schema::hasColumn('contracts', 'has_turnaround_time')) {
            Schema::table('contracts', function (Blueprint $table): void {
                $table->boolean('has_turnaround_time')->default(true)->after('turnaround_days');
            });
        }
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table): void {
            $table->dropColumn([
                'assigned_to',
                'attachment',
                'turnaround_days',
                'has_turnaround_time',
            ]);
        });
    }
};

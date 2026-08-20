<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directory_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_department_id')
                ->nullable()
                ->constrained('department_modules')
                ->onDelete('cascade');
            $table->string('poc_name_id')->nullable();
            $table->string('poc_job_position')->nullable();
            $table->longText('poc_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_modules');
    }
};

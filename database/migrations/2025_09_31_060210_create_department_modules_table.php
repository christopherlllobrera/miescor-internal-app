<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_modules', function (Blueprint $table) {
            $table->id();
            $table->string('cms_department_name')->nullable();
            $table->longText('cms_department_description')->nullable();
            $table->string('cms_department_slug')->nullable();
            $table->longText('cms_banner')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_modules');
    }
};

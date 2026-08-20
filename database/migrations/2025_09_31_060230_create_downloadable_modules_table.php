<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloadable_modules', function (Blueprint $table) {
            $table->id();
            $table->string('cms_department_id');
            $table->string('form_title')->nullable();
            $table->longText('form_attachment')->nullable();
            $table->longtext('form_icon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloadable_modules');
    }
};

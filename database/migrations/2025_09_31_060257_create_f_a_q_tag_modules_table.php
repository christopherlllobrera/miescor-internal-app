<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('f_a_q_tag_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_department_id')
                ->nullable()
                ->constrained('department_modules')
                ->onDelete('cascade');
            $table->string('faq_tag_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('f_a_q_tag_modules');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('f_a_q_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_department_id')
                ->nullable()
                ->constrained('department_modules')
                ->onDelete('cascade');
            $table->foreignId('faq_tag_id')
                ->nullable()
                ->constrained('f_a_q_tag_modules')
                ->onDelete('set null');
            $table->string('faq_title')->nullable();
            $table->string('faq_slug')->nullable();
            $table->longText('faq_body')->nullable();
            $table->boolean('faq_is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('f_a_q_modules');
    }
};

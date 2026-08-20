<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_department_id')
                ->nullable()
                ->constrained('department_modules')
                ->onDelete('cascade');
            $table->foreignId('workflow_tag_id')
                ->nullable()
                ->constrained('workflow_tag_modules')
                ->onDelete('set null');
            $table->string('workflow_title')->nullable();
            $table->string('workflow_slug')->nullable();
            $table->longText('workflow_body')->nullable();
            $table->boolean('workflow_is_published')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_modules');
    }
};

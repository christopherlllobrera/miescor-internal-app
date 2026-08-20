<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department_modules', function (Blueprint $table) {
            $table->string('cms_icon')->nullable()->after('cms_department_slug');
            // $table->string('cms_department')->nullable()->after('cms_icon');
        });
    }

    public function down(): void
    {
        Schema::table('department_modules', function (Blueprint $table) {
            $table->dropColumn('cms_icon');
        });
    }
};

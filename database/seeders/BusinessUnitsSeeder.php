<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BusinessUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/sql/tblBusinessUnits.sql');
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            $sql = str_replace("\xEF\xBB\xBF", '', $sql);
            DB::unprepared($sql);
        }
    }
}

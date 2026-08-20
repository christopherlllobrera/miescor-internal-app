<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlPath = database_path('seeders/sql/tblLocationA.sql');
        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            $sql = str_replace("\xEF\xBB\xBF", '', $sql);
            $sql = str_replace('`tblLocationA`', '`tblLocation`', $sql);
            DB::unprepared($sql);
        }
    }
}

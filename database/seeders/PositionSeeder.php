<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = database_path('seeders/sql/tblPosition.sql');

        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            $sql = str_replace("\xEF\xBB\xBF", '', $sql);
            $sql = str_replace("'0000-00-00 00:00:00'", 'NULL', $sql);
            $sql = str_replace('INSERT INTO `tblPosition`', 'INSERT IGNORE INTO `tblPosition`', $sql);
            DB::connection()->getPdo()->exec($sql);
        }
    }
}

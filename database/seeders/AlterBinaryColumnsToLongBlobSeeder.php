<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlterBinaryColumnsToLongBlobSeeder extends Seeder
{
    /**
     * Alter binary columns to LONGBLOB for large file storage.
     */
    public function run(): void
    {
        $alterations = [
            'ALTER TABLE users MODIFY avatar_url LONGBLOB NULL',
            'ALTER TABLE posts MODIFY image LONGBLOB NULL',
            'ALTER TABLE department_modules MODIFY cms_banner LONGBLOB NULL',
            'ALTER TABLE downloadable_modules MODIFY form_attachment LONGBLOB NULL',
            'ALTER TABLE directory_modules MODIFY poc_image LONGBLOB NULL',
            'ALTER TABLE leave_requests MODIFY attachment LONGBLOB NULL',
            'ALTER TABLE carousels MODIFY image LONGBLOB NULL',
            'ALTER TABLE tblEmployee MODIFY ItemPict LONGBLOB NULL',
        ];

        foreach ($alterations as $sql) {
            DB::statement($sql);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FAQModulesSeeder extends Seeder
{
    public function run()
    {
        // Ensure faq_body can hold long content. Attempt to modify column to LONGTEXT.
        try {
            DB::statement('ALTER TABLE `f_a_q_modules` MODIFY `faq_body` LONGTEXT NULL;');
            $this->command->info('Ensured f_a_q_modules.faq_body is LONGTEXT');
        } catch (\Exception $e) {
            // If the driver doesn't support MODIFY or column already longtext, skip silently but log.
            $this->command->info('Skipping altering faq_body column: '.$e->getMessage());
        }

        $path = database_path('seeders/data/faq_modules.csv');

        if (! file_exists($path)) {
            $this->command->error('CSV file not found: '.$path);

            return;
        }

        $handle = fopen($path, 'r');

        // Skip header
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            // Take only the first 6 columns (ignore extra commas)
            $row = array_slice($row, 0, 6);

            DB::table('f_a_q_modules')->insert([
                'cms_department_id' => $row[0] !== '' ? $row[0] : null,
                'faq_tag_id' => $row[1] !== '' ? $row[1] : null,
                'faq_title' => $row[2] ?? null,
                'faq_slug' => $row[3] !== '' ? $row[3] : Str::slug($row[2]),
                'faq_body' => $row[4] ?? null,
                // If the CSV column is missing or empty, default to published (1)
                'faq_is_published' => (isset($row[5]) && $row[5] !== '') ? (int) $row[5] : 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($handle);
    }
}

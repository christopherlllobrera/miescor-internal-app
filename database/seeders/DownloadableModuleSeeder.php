<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadableModuleSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/assets/downloadable.csv');
        $sourceDir = database_path('seeders/assets/DownloadableModules');
        $disk = 'public_images';
        $targetDir = 'downloadable_attachments';

        // Ensure target directory exists
        Storage::disk($disk)->makeDirectory($targetDir);

        $rows = array_map('str_getcsv', file($csvPath));

        foreach ($rows as $row) {
            // Skip invalid / short rows
            if (count($row) < 4) {
                continue;
            }

            [$cmsDepartmentId, $formTitle, $filename, $formIcon] = $row;

            $filename = trim($filename);

            // Skip if no attachment filename
            if (! $filename) {
                continue;
            }

            $sourceFile = $sourceDir.'/'.$filename;
            $targetFile = $targetDir.'/'.$filename;

            // Copy file only if it exists and not already copied
            if (File::exists($sourceFile) && ! Storage::disk($disk)->exists($targetFile)) {
                Storage::disk($disk)->put(
                    $targetFile,
                    File::get($sourceFile)
                );
            }

            DB::table('downloadable_modules')->insert([
                'cms_department_id' => trim($cmsDepartmentId),
                'form_title' => trim($formTitle),
                // Store attachment as JSON array so Eloquent 'array' cast works
                'form_attachment' => json_encode([$targetFile]),
                'form_icon' => trim($formIcon),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

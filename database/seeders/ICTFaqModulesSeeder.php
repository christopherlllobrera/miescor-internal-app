<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ICTFaqModulesSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/ict_faq.csv');

        if (! file_exists($path)) {
            $this->command->error("CSV file not found at: {$path}");

            return;
        }

        DB::beginTransaction();

        try {
            $handle = fopen($path, 'r');
            if ($handle === false) {
                throw new \RuntimeException("Unable to open CSV: {$path}");
            }

            // Read header
            $header = fgetcsv($handle);
            if (! $header) {
                throw new \RuntimeException("CSV appears empty or unreadable: {$path}");
            }

            // Normalize header keys
            $header = array_map(function ($h) {
                return trim(str_replace(["\xEF\xBB\xBF"], '', $h)); // remove BOM if present
            }, $header);

            $rowNum = 1; // including header
            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                // Skip entirely blank lines
                if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                    continue;
                }

                $data = array_combine($header, $row);

                // Trim all string fields
                $data = array_map(function ($v) {
                    return is_string($v) ? trim($v) : $v;
                }, $data);

                // Map columns (handle missing keys safely)
                $id = $data['id'] ?? null;
                $cmsDepartmentId = $this->nullableInt($data['cms_department_id'] ?? null);
                $faqTagId = $this->nullableInt($data['faq_tag_id'] ?? null);
                $faqTitle = $data['faq_title'] ?? null;
                $faqSlug = $data['faq_slug'] ?? null;
                $faqBody = $data['faq_body'] ?? null;
                $faqIsPublishedRaw = $data['faq_is_published'] ?? 0;
                $createdAtRaw = $data['created_at'] ?? null;
                $updatedAtRaw = $data['updated_at'] ?? null;

                // Skip if required fields are missing
                if (! $faqTitle) {
                    $this->command->warn("Row {$rowNum}: Missing faq_title — skipped.");
                    $skipped++;

                    continue;
                }

                // Slug: generate if empty
                if (! $faqSlug) {
                    $faqSlug = Str::slug($faqTitle);
                }

                // Boolean cast for `faq_is_published`
                $faqIsPublished = $this->toBool($faqIsPublishedRaw);

                // Parse timestamps (allow empty)
                $createdAt = $this->parseTimestamp($createdAtRaw);
                $updatedAt = $this->parseTimestamp($updatedAtRaw);

                $now = Carbon::now();
                if (! $createdAt) {
                    $createdAt = $now;
                }
                if (! $updatedAt) {
                    $updatedAt = $now;
                }

                // Build the payload
                $payload = [
                    'cms_department_id' => $cmsDepartmentId,
                    'faq_tag_id' => $faqTagId,
                    'faq_title' => $faqTitle,
                    'faq_slug' => $faqSlug,
                    'faq_body' => $faqBody,
                    'faq_is_published' => $faqIsPublished ? 1 : 0,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];

                // Choose a unique match:
                // 1) Prefer explicit id (if provided and numeric)
                // 2) Otherwise, match by (faq_title + cms_department_id + faq_tag_id)
                $matchedById = false;
                if ($this->nullableInt($id) !== null) {
                    $existing = DB::table('f_a_q_modules')->where('id', $id)->first();
                    if ($existing) {
                        DB::table('f_a_q_modules')->where('id', $id)->update($payload);
                        $updated++;
                        $matchedById = true;
                    } else {
                        // Insert with explicit id (keeps incoming ids aligned if desired)
                        $payloadWithId = array_merge(['id' => (int) $id], $payload);
                        DB::table('f_a_q_modules')->insert($payloadWithId);
                        $inserted++;
                        $matchedById = true;
                    }
                }

                if (! $matchedById) {
                    // Fallback uniqueness key
                    $existing = DB::table('f_a_q_modules')
                        ->where('faq_title', $faqTitle)
                        ->where(function ($q) use ($cmsDepartmentId) {
                            if ($cmsDepartmentId === null) {
                                $q->whereNull('cms_department_id');
                            } else {
                                $q->where('cms_department_id', $cmsDepartmentId);
                            }
                        })
                        ->where(function ($q) use ($faqTagId) {
                            if ($faqTagId === null) {
                                $q->whereNull('faq_tag_id');
                            } else {
                                $q->where('faq_tag_id', $faqTagId);
                            }
                        })
                        ->first();

                    if ($existing) {
                        DB::table('f_a_q_modules')->where('id', $existing->id)->update($payload);
                        $updated++;
                    } else {
                        DB::table('f_a_q_modules')->insert($payload);
                        $inserted++;
                    }
                }
            }

            fclose($handle);
            DB::commit();

            $this->command->info("FAQ seeding complete. Inserted: {$inserted}, Updated: {$updated}, Skipped: {$skipped}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('FAQ seeding failed: '.$e->getMessage());
            throw $e;
        }
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        $v = strtolower(trim((string) $value));

        return in_array($v, ['1', 'true', 'yes', 'y'], true);
    }

    private function parseTimestamp($value): ?Carbon
    {
        if (! $value) {
            return null;
        }
        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function nullableInt($value): ?int
    {
        if ($value === '' || $value === null) {
            return null;
        }
        if (! is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }
}

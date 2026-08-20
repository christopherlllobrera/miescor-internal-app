<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqTagModulesSeeder extends Seeder
{
    public function run(): void
    {
        // Raw tags from your message
        $rawTags = [
            'Network & Connectivity',
            'Passwords & Account Access',
            'Email & Office 365',
            'Security & MFA',
            'Accounts & Access',
            'Performance Issues',
            'Connectivity',
            'Email & Communication',
            'Printing ',
            'Software & Access Requests',
            'Service Desk Assistance',
            'SAP System Availability',
            'SAP Freezing / Crashing',
            'SAP Printing',                 // duplicate
            'SAP Performance',
            'SAP Connectivity',
            'SAP Security',
        ];

        // 1) Trim and normalize whitespace
        $normalized = array_map(function ($t) {
            // Collapse internal whitespace and trim ends
            $t = preg_replace('/\s+/u', ' ', (string) $t);

            return trim($t);
        }, $rawTags);

        // 2) Remove empty and de-duplicate while preserving original case
        $uniqueTags = [];
        foreach ($normalized as $t) {
            if ($t === '') {
                continue;
            }
            // Case-insensitive dedupe: keep the first seen version
            $key = mb_strtolower($t, 'UTF-8');
            if (! array_key_exists($key, $uniqueTags)) {
                $uniqueTags[$key] = $t;
            }
        }

        $now = Carbon::now();
        $rows = [];
        foreach (array_values($uniqueTags) as $name) {
            $rows[] = [
                'faq_tag_name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Use upsert for idempotency; requires a unique index on faq_tag_name
        // If you chose not to add the unique index, replace this with a manual loop + updateOrInsert
        DB::table('f_a_q_tag_modules')->upsert(
            $rows,
            ['faq_tag_name'],   // conflict target
            ['updated_at']      // columns to update on conflict
        );

        $this->command?->info('FAQ tags seeded/updated: '.count($rows));
    }
}

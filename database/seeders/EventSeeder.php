<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'title' => 'MIESCOR 2025 Kick Off',
                'description' => 'Unleash The Power! Together, We Conquer!',
                'date' => '2026-02-04',
                'color' => 'blue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "Women's Month",
                'description' => 'Leading the change: Women shaping a sustainable future',
                'date' => '2026-03-06 ',
                'color' => 'violet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "Mother's Day",
                'description' => 'Honoring the mother of the family or individual, as well as motherhood, maternal bonds, and the influence of mothers in society.',
                'date' => '2026-05-10',
                'color' => 'pink',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => "Father's Day",
                'description' => "This Father's Day, we honor and appreciate the dedication and influence of fathers and father figures within our organization.",
                'date' => '2026-06-21',
                'color' => 'green',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Halloween Party',
                'description' => 'Get ready for a spooktacular time! Join us for our employee Halloween event!',
                'date' => '2026-10-30',
                'color' => 'black',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'MIESCOR Anniversary',
                'description' => "We're thrilled to commemorate 52 years of MIESCOR! Please join us for a celebration as we reflect on our journey and look forward to the future.",
                'date' => '2026-12-05',
                'color' => 'orange',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

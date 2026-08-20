<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarouselSeeder extends Seeder
{
    public function run(): void
    {
        $carousels = [
            [
                'title' => 'Recognizing Excellence',
                'subtitle' => 'At MIESCOR, we are committed to celebrating employees who demonstrate outstanding performance and dedication through our various recognition programs.',
                'button_text' => 'Learn more',
                'button_link' => 'https://miescor.sharepoint.com/sites/miescorhr/SitePages/Talent-Development-and-Engagement.aspx',
                'sort_order' => 1,
            ],
            [
                'title' => 'Honing Talents, Sharpening Skills',
                'subtitle' => 'At MIESCOR, we provide holistic training programs that equip employees with the skills and knowledge needed to excel and contribute to the company\'s success.',
                'button_text' => 'Learn more',
                'button_link' => 'https://miescor.sharepoint.com/sites/miescorhr/SitePages/Talent-Development-and-Engagement.aspx',
                'sort_order' => 2,
            ],
            [
                'title' => 'Building Connections, Fostering Belonging',
                'subtitle' => 'At MIESCOR, we nurture a workplace where people feel connected and cared for — not just through the work we do, but through shared moments of fun that strengthen relationships and cultivate a sense of belonging for everyone.',
                'button_text' => 'Learn more',
                'button_link' => 'https://miescor.sharepoint.com/sites/miescorhr/SitePages/Talent-Development-and-Engagement.aspx',
                'sort_order' => 3,
            ],
        ];

        foreach ($carousels as $carousel) {
            DB::table('carousels')->insert([
                'title' => $carousel['title'],
                'subtitle' => $carousel['subtitle'],
                'image' => null,
                'button_text' => $carousel['button_text'],
                'button_link' => $carousel['button_link'],
                'is_active' => true,
                'sort_order' => $carousel['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

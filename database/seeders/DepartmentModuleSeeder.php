<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentModuleSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'dept_no' => 'MIEH100',
                'slug' => 'corporate-hr-and-transformation',
                'desc' => '',
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'dept_no' => 'MIEK100',
                'slug' => 'occupational-safety-and-sustainability-management',
                'desc' => '',
                'icon' => 'heroicon-o-shield-check',
            ],
            [
                'dept_no' => 'MIEP100',
                'slug' => 'commercial-services',
                'desc' => '',
                'icon' => 'heroicon-o-briefcase',
            ],
            [
                'dept_no' => 'MIEO100',
                'slug' => 'logistics-management',
                'desc' => 'The strategic planning, coordination, and control of the movement, storage, distribution, fleet operations, and asset management to ensure efficient operations, cost optimization, and timely support of organizational objectives.',
                'icon' => 'heroicon-o-truck',
            ],
            [
                'dept_no' => 'MIEG100',
                'slug' => 'ict',
                'desc' => 'The Department of Information, Communication and Technology manages networks, systems, data and security, provides user support, drives digital innovation, and ensures reliable, efficient and secure technology services that help the organization succeeds.',
                'icon' => 'heroicon-o-computer-desktop',
            ],
            [
                'dept_no' => 'MIEB100',
                'slug' => 'corporate-services',
                'desc' => 'The Corporate Services Group drives organizational objectives by initiating corporate process management, leading strategic planning efforts, and delivering reliable administrative services to promote operational efficiency and ensure alignment with company goals.',
                'icon' => 'heroicon-o-building-office',
            ],
            [
                'dept_no' => 'MIEE100',
                'slug' => 'finance',
                'desc' => '',
                'icon' => 'heroicon-o-banknotes',
            ],
            [
                'dept_no' => 'MIEF100',
                'slug' => 'legal',
                'desc' => '',
                'icon' => 'heroicon-o-scale',
            ],
            [
                'dept_no' => 'MIEI100',
                'slug' => 'corporate-labor-relations',
                'desc' => 'Corporate Labor Relations is composed of two primary units: Labor Relations and Security Services. These units work in collaboration to promote a harmonious workplace, ensure compliance with labor laws and regulations, and uphold organizational integrity by effectively managing employee relations and safeguarding company assets.',
                'icon' => 'heroicon-o-hand-raised',
            ],
            [
                'dept_no' => 'MIED100',
                'slug' => 'supply-chain-management',
                'desc' => '',
                'icon' => 'heroicon-o-arrows-right-left',
            ],
            [
                'dept_no' => 'MIEL100',
                'slug' => 'quality-assurance-and-control',
                'desc' => '',
                'icon' => 'heroicon-o-check-badge',
            ],
            [
                'dept_no' => 'MIEN100',
                'slug' => 'project-engineering-and-execution',
                'desc' => 'The Engineering, Procurement, and Construction (EPC) Business Unit is the largest division of MIESCOR, delivering end-to-end solutions for energy and infrastructure projects. We design, supply equipment and materials, and build facilities and infrastructure related to power, utilities, and transportation, ensuring the timely and safe completion of high-quality projects. Our expertise spans substations, transmission lines, sub-transmission lines, and other critical developments, including multiple projects supporting renewable energy initiatives.',
                'icon' => 'heroicon-o-wrench-screwdriver',
            ],
            [
                'dept_no' => 'MIEA100',
                'slug' => 'president-and-ceo',
                'desc' => '',
                'icon' => 'heroicon-o-user-circle',
            ],
            [
                'dept_no' => 'MIEC100',
                'slug' => 'corporate-audit',
                'desc' => '',
                'icon' => 'heroicon-o-clipboard-document-check',
            ],
            [
                'dept_no' => 'MIEQ100',
                'slug' => 'operations-group',
                'desc' => '',
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
        ];

        foreach ($departments as $dept) {
            DB::table('department_modules')->insert([
                'cms_department_name' => $dept['dept_no'], // Stores DeptNo as per form logic
                'cms_department_slug' => $dept['slug'] ?: Str::slug($dept['full']),
                'cms_department_description' => $dept['desc'] ?? '',
                'cms_icon' => $dept['icon'] ?? '',
                'cms_banner' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

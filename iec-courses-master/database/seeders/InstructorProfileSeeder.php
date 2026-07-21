<?php

namespace Database\Seeders;

use App\Models\InstructorProfile;
use Illuminate\Database\Seeder;

class InstructorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructors = [
            [
                'name' => 'Mufti Ali Asghar',
                'title' => 'Head of Islamic Economics Centre',
                'bio' => 'Mufti Ali Asghar is a Senior Mufti at Dar-al-Ifta Ahl-e-Sunnah with over 20 years of experience in Islamic finance and Shariah-compliant economics. He specializes in Islamic banking, contracts, and Shariah governance.',
                'expertise' => 'Islamic Banking,Shariah Compliance,Islamic Contracts,Economic Jurisprudence',
                'skills' => 'Shariah Auditing,Fatwa Issuance,Islamic Financial Structuring,Currency Transactions',
                'image_path' => null,
                'social_linkedin' => 'https://linkedin.com/in/muftiali-asghar',
                'social_twitter' => null,
                'social_website' => 'https://daraliftaahlesunnah.org',
                'is_active' => true,
            ],
            [
                'name' => 'Mufti Muhammad Sajjad',
                'title' => 'Senior Mufti at Dar-al-Ifta Ahl-e-Sunnah',
                'bio' => 'Mufti Muhammad Sajjad is a renowned scholar at Dar-al-Ifta Ahl-e-Sunnah, with extensive expertise in Islamic jurisprudence and Shariah-compliant financial instruments like Mudarabah and Murabaha.',
                'expertise' => 'Islamic Jurisprudence,Mudarabah,Murabaha,Shariah Rulings',
                'skills' => 'Fatwa Issuance,Shariah Contract Analysis,Islamic Investment Tools',
                'image_path' => null,
                'social_linkedin' => null,
                'social_twitter' => null,
                'social_website' => 'https://daraliftaahlesunnah.org',
                'is_active' => true,
            ],
            [
                'name' => 'Mufti Muhammad Jamil',
                'title' => 'Mufti at Dar-al-Ifta Ahl-e-Sunnah',
                'bio' => 'Mufti Muhammad Jamil is a distinguished scholar at Dar-al-Ifta Ahl-e-Sunnah, specializing in the prohibition of Riba and contemporary Islamic finance practices.',
                'expertise' => 'Riba Prohibition,Islamic Finance,Shariah Compliance,Legal Personality',
                'skills' => 'Shariah Rulings,Fatwa Issuance,Islamic Legal Analysis',
                'image_path' => null,
                'social_linkedin' => null,
                'social_twitter' => null,
                'social_website' => 'https://daraliftaahlesunnah.org',
                'is_active' => true,
            ],
            [
                'name' => 'Maulana Farhan',
                'title' => 'Scholar at Dar-al-Ifta Ahl-e-Sunnah',
                'bio' => 'Maulana Farhan is an expert in Shariah-compliant investment tools and contracts, contributing to educational initiatives at Dar-al-Ifta Ahl-e-Sunnah.',
                'expertise' => 'Investment Tools,Istisna,Salam Contracts,Mutual Funds',
                'skills' => 'Shariah Contract Structuring,Islamic Investment Analysis',
                'image_path' => null,
                'social_linkedin' => null,
                'social_twitter' => null,
                'social_website' => 'https://daraliftaahlesunnah.org',
                'is_active' => true,
            ],
            [
                'name' => 'Maulana Talha Siddiqui',
                'title' => 'Head of Shariah Audit',
                'bio' => 'Maulana Talha Siddiqui serves as Head of Shariah Audit at a leading private Islamic bank, with expertise in agency, partnership, and Shariah-compliant banking operations.',
                'expertise' => 'Shariah Auditing,Agency (Wakalah),Partnership (Shirkah),Islamic Banking',
                'skills' => 'Shariah Compliance Auditing,Contract Review,Islamic Financial Operations',
                'image_path' => null,
                'social_linkedin' => 'https://linkedin.com/in/talha-siddiqui',
                'social_twitter' => null,
                'social_website' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Muhammad Younus Ali',
                'title' => 'Shariah Board Member',
                'bio' => 'Dr. Muhammad Younus Ali is a member of the Shariah Board at a private Takaful company, specializing in contracts, guarantees, and Shariah-compliant insurance products.',
                'expertise' => 'Takaful,Contracts,Guarantees,Shariah Compliance',
                'skills' => 'Shariah Contract Analysis,Takaful Product Structuring,Guarantee Systems',
                'image_path' => null,
                'social_linkedin' => 'https://linkedin.com/in/muhammad-younus-ali',
                'social_twitter' => null,
                'social_website' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Maulana Zain-ul-Abidin',
                'title' => 'Shariah Auditor',
                'bio' => 'Maulana Zain-ul-Abidin is a Shariah Auditor at a private bank, with deep knowledge of Islamic sales contracts and contemporary financial challenges.',
                'expertise' => 'Shariah Auditing,Sales Contracts,Islamic Finance,Contemporary Issues',
                'skills' => 'Shariah Compliance Auditing,Contract Analysis,Sales Rulings',
                'image_path' => null,
                'social_linkedin' => null,
                'social_twitter' => null,
                'social_website' => null,
                'is_active' => true,
            ],
        ];

        foreach ($instructors as $instructor) {
            InstructorProfile::updateOrCreate(
                ['name' => $instructor['name']],
                $instructor
            );
        }
    }
}
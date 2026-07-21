<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lecture;
use App\Models\LectureFeature;
use Illuminate\Support\Facades\DB;

class LectureFeatureSeeder extends Seeder
{
    public function run()
    {
        // Clear existing features
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lecture_features')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all lectures
        $lectures = Lecture::all();

        foreach ($lectures as $lecture) {
            // Seed features based on lecture title
            $this->seedLectureFeatures($lecture);
        }
    }

    private function seedLectureFeatures($lecture)
    {
        // Add learn features
        $this->addLearnFeatures($lecture);

        // Add requirement features
        $this->addRequirementFeatures($lecture);
    }

    private function addLearnFeatures($lecture)
    {
        $learnFeatures = $this->getLearnFeatures($lecture->name);
        $sortOrder = 1;

        foreach ($learnFeatures as $feature) {
            LectureFeature::create([
                'lecture_id' => $lecture->id,
                'feature_type' => 'learn',
                'feature_text' => $feature,
                'sort_order' => $sortOrder++
            ]);
        }
    }

    private function addRequirementFeatures($lecture)
    {
        $requirementFeatures = $this->getRequirementFeatures($lecture->name);
        $sortOrder = 1;

        foreach ($requirementFeatures as $feature) {
            LectureFeature::create([
                'lecture_id' => $lecture->id,
                'feature_type' => 'requirement',
                'feature_text' => $feature,
                'sort_order' => $sortOrder++
            ]);
        }
    }

    private function getLearnFeatures($lectureName)
    {
        switch ($lectureName) {
            // Islamic Banking and Finance
            case 'Currency Transactions in Islamic Banking':
                return [
                    'Understand Shariah-compliant currency exchange principles',
                    'Analyze permissible transaction structures',
                    'Evaluate case studies on currency transactions'
                ];

            case 'Dollar Booking and Shariah Compliance':
                return [
                    'Explore Shariah rules for dollar booking',
                    'Understand compliance requirements in banking',
                    'Apply Shariah principles to financial bookings'
                ];

            case 'Letter of Credit (LC) Amount Collection':
                return [
                    'Learn Shariah rulings on LC collections',
                    'Understand advance payment structures',
                    'Analyze real-world LC scenarios'
                ];

            case 'Shariah Perspective on Bank Card Types':
                return [
                    'Identify Shariah-compliant bank card types',
                    'Analyze card usage in Islamic finance',
                    'Understand Shariah rulings on card transactions'
                ];

            case 'Treasury of Islamic Banks':
                return [
                    'Understand treasury operations in Islamic banks',
                    'Explore Shariah-compliant treasury strategies',
                    'Analyze case studies on treasury management'
                ];

            // Modern Applications of Mudarabah and Murabaha
            case 'Introduction to Mudarabah':
                return [
                    'Grasp the core principles of Mudarabah',
                    'Understand profit-sharing mechanisms',
                    'Analyze Mudarabah contract structures'
                ];

            case 'Use of Mudarabah in Contemporary Investment':
                return [
                    'Apply Mudarabah in modern investments',
                    'Evaluate risk-sharing in Mudarabah contracts',
                    'Analyze real-world Mudarabah applications'
                ];

            case 'Introduction to Murabaha':
                return [
                    'Understand the structure of Murabaha contracts',
                    'Learn cost-plus financing principles',
                    'Analyze Murabaha transaction requirements'
                ];

            case 'Use of Murabaha in Contemporary Investment':
                return [
                    'Apply Murabaha in modern financial scenarios',
                    'Evaluate Murabaha compliance with Shariah',
                    'Analyze case studies on Murabaha applications'
                ];

            // Riba (Interest) and its Contemporary Forms
            case 'Definition of Riba':
                return [
                    'Define Riba and its implications',
                    'Understand Shariah prohibitions on Riba',
                    'Explore historical context of Riba'
                ];

            case 'Types of Riba':
                return [
                    'Identify different types of Riba',
                    'Understand Riba al-Nasi’ah and Riba al-Fadl',
                    'Analyze distinctions in Riba categories'
                ];

            case 'Examples of Riba in the Modern Era':
                return [
                    'Identify Riba in contemporary finance',
                    'Analyze modern financial products for Riba',
                    'Evaluate strategies to avoid Riba'
                ];

            case 'Shariah Rulings on Riba':
                return [
                    'Understand Islamic legal perspectives on Riba',
                    'Analyze fatwas related to Riba',
                    'Apply Shariah rulings to financial practices'
                ];

            // Investment Tools in Shariah
            case 'Istisna Contract and Its Role':
                return [
                    'Understand Istisna contract principles',
                    'Analyze Istisna applications in investment',
                    'Evaluate Istisna compliance with Shariah'
                ];

            case 'Salam Contract as a Business Solution':
                return [
                    'Learn Salam contract structures',
                    'Apply Salam in modern business scenarios',
                    'Analyze Salam contract case studies'
                ];

            case 'What are Mutual Fund Institutions?':
                return [
                    'Understand mutual funds in Islamic finance',
                    'Explore Shariah-compliant fund structures',
                    'Analyze roles of mutual fund institutions'
                ];

            case 'How Mutual Funds Work in Shariah':
                return [
                    'Learn operational mechanics of Shariah funds',
                    'Analyze profit distribution in mutual funds',
                    'Evaluate Shariah-compliant fund strategies'
                ];

            // Legal Personality in Islamic Law
            case 'Concept of Legal Person':
                return [
                    'Define legal personality in Shariah',
                    'Understand Shariah views on legal entities',
                    'Analyze legal person concepts'
                ];

            case 'Shariah Status of Legal Persons':
                return [
                    'Explore recognition of legal persons in Shariah',
                    'Analyze Shariah-compliant corporate entities',
                    'Evaluate legal person case studies'
                ];

            case 'Applications in Islamic Finance':
                return [
                    'Apply legal personality in Islamic finance',
                    'Understand corporate structures in Shariah',
                    'Analyze real-world financial applications'
                ];

            // Agency and Partnership in Contemporary Context
            case 'Agency in Contemporary Issues':
                return [
                    'Understand Wakalah in modern finance',
                    'Analyze agency contract applications',
                    'Evaluate contemporary Wakalah scenarios'
                ];

            case 'Partnership in Contemporary Issues':
                return [
                    'Learn Shirkah principles in modern contexts',
                    'Analyze partnership contract applications',
                    'Evaluate Shirkah case studies'
                ];

            case 'Principles of Agency in Shariah':
                return [
                    'Understand core principles of Wakalah',
                    'Analyze Shariah-compliant agency rules',
                    'Apply agency principles to contracts'
                ];

            case 'Principles of Partnership in Shariah':
                return [
                    'Learn core principles of Shirkah',
                    'Analyze Shariah-compliant partnership rules',
                    'Apply partnership principles to contracts'
                ];

            // Contracts and Guarantees in Islamic Finance
            case 'Services and Their Compensation':
                return [
                    'Understand service contracts in Shariah',
                    'Analyze compensation structures',
                    'Evaluate modern service contract scenarios'
                ];

            case 'Guarantee System in Bank Agreements':
                return [
                    'Learn guarantee systems in Islamic banking',
                    'Analyze Shariah-compliant guarantees',
                    'Evaluate banking agreement case studies'
                ];

            case 'Basic Rulings of Sales':
                return [
                    'Understand fundamental sales principles',
                    'Analyze Shariah-compliant sales rules',
                    'Apply sales rulings to contracts'
                ];

            case 'Contemporary Issues in Sales':
                return [
                    'Address modern challenges in sales contracts',
                    'Analyze Shariah-compliant sales issues',
                    'Evaluate contemporary sales case studies'
                ];

            default:
                return [
                    'Understand key concepts in Islamic finance',
                    'Analyze Shariah-compliant principles',
                    'Apply concepts to practical scenarios'
                ];
        }
    }

    private function getRequirementFeatures($lectureName)
    {
        switch ($lectureName) {
            // Islamic Banking and Finance
            case 'Currency Transactions in Islamic Banking':
            case 'Dollar Booking and Shariah Compliance':
            case 'Letter of Credit (LC) Amount Collection':
            case 'Shariah Perspective on Bank Card Types':
            case 'Treasury of Islamic Banks':
                return [
                    'Basic understanding of Islamic finance principles',
                    'Access to a computer with internet connection'
                ];

            // Modern Applications of Mudarabah and Murabaha
            case 'Introduction to Mudarabah':
            case 'Use of Mudarabah in Contemporary Investment':
            case 'Introduction to Murabaha':
            case 'Use of Murabaha in Contemporary Investment':
                return [
                    'Basic knowledge of Islamic finance contracts',
                    'Access to a computer with internet connection'
                ];

            // Riba (Interest) and its Contemporary Forms
            case 'Definition of Riba':
            case 'Types of Riba':
            case 'Examples of Riba in the Modern Era':
            case 'Shariah Rulings on Riba':
                return [
                    'No prior knowledge of Riba required',
                    'Access to a computer with internet connection'
                ];

            // Investment Tools in Shariah
            case 'Istisna Contract and Its Role':
            case 'Salam Contract as a Business Solution':
            case 'What are Mutual Fund Institutions?':
            case 'How Mutual Funds Work in Shariah':
                return [
                    'Basic understanding of Islamic investment tools',
                    'Access to a computer with internet connection'
                ];

            // Legal Personality in Islamic Law
            case 'Concept of Legal Person':
            case 'Shariah Status of Legal Persons':
            case 'Applications in Islamic Finance':
                return [
                    'Basic interest in Islamic law concepts',
                    'Access to a computer with internet connection'
                ];

            // Agency and Partnership in Contemporary Context
            case 'Agency in Contemporary Issues':
            case 'Partnership in Contemporary Issues':
            case 'Principles of Agency in Shariah':
            case 'Principles of Partnership in Shariah':
                return [
                    'Basic knowledge of Islamic finance or law helpful',
                    'Access to a computer with internet connection'
                ];

            // Contracts and Guarantees in Islamic Finance
            case 'Services and Their Compensation':
            case 'Guarantee System in Bank Agreements':
            case 'Basic Rulings of Sales':
            case 'Contemporary Issues in Sales':
                return [
                    'Basic understanding of Islamic contracts',
                    'Access to a computer with internet connection'
                ];

            default:
                return [
                    'Basic computer skills required',
                    'Internet connection for accessing course materials'
                ];
        }
    }
}
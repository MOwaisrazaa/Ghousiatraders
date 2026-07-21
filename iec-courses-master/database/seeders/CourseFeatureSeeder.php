<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseFeature;
use Illuminate\Support\Facades\DB;

class CourseFeatureSeeder extends Seeder
{
    public function run()
    {
        // Clear existing features
        DB::table('course_features')->truncate();

        // Get all courses
        $courses = Course::all();

        foreach ($courses as $course) {
            // Seed features based on course name
            $this->seedCourseFeatures($course);
        }
    }

    private function seedCourseFeatures($course)
    {
        // What You'll Learn features
        $learnFeatures = $this->getLearnFeatures($course->name);
        $sortOrder = 1;
        foreach ($learnFeatures as $feature) {
            CourseFeature::create([
                'course_id' => $course->id,
                'feature_text' => $feature,
                'feature_type' => 'learn',
                'sort_order' => $sortOrder++
            ]);
        }

        // Requirements features
        $requirementFeatures = $this->getRequirementFeatures($course->name);
        $sortOrder = 1;
        foreach ($requirementFeatures as $feature) {
            CourseFeature::create([
                'course_id' => $course->id,
                'feature_text' => $feature,
                'feature_type' => 'requirement',
                'sort_order' => $sortOrder++
            ]);
        }

        // Includes features
        $includeFeatures = $this->getIncludeFeatures($course->name);
        $sortOrder = 1;
        foreach ($includeFeatures as $feature) {
            CourseFeature::create([
                'course_id' => $course->id,
                'feature_text' => $feature,
                'feature_type' => 'includes',
                'sort_order' => $sortOrder++
            ]);
        }
    }

    private function getLearnFeatures($courseName)
    {
        switch ($courseName) {
            case 'Islamic Banking and Finance':
                return [
                    'Understand the core principles of Islamic banking',
                    'Analyze Shariah-compliant financial transactions',
                    'Evaluate currency exchange practices in Islamic finance',
                    'Explore treasury operations in Islamic banks',
                    'Apply Shariah rulings to modern banking scenarios',
                    'Master the use of bank cards under Shariah compliance'
                ];

            case 'Modern Applications of Mudarabah and Murabaha':
                return [
                    'Grasp the fundamentals of Mudarabah and Murabaha contracts',
                    'Apply Mudarabah in modern investment scenarios',
                    'Implement Murabaha in contemporary financial transactions',
                    'Analyze risk-sharing mechanisms in Islamic finance',
                    'Evaluate practical case studies of Mudarabah and Murabaha'
                ];

            case 'Riba (Interest) and its Contemporary Forms':
                return [
                    'Define Riba and its implications in Islamic law',
                    'Identify various types of Riba in modern finance',
                    'Analyze contemporary examples of Riba in financial systems',
                    'Understand Shariah rulings on avoiding Riba',
                    'Develop strategies to ensure Riba-free transactions'
                ];

            case 'Investment Tools in Shariah':
                return [
                    'Master Shariah-compliant investment instruments',
                    'Understand Istisna and Salam contracts',
                    'Explore the structure of Shariah-compliant mutual funds',
                    'Apply investment tools to real-world scenarios',
                    'Evaluate the role of mutual funds in Islamic finance'
                ];

            case 'Legal Personality in Islamic Law':
                return [
                    'Define the concept of legal personality in Shariah',
                    'Analyze the status of legal persons in Islamic law',
                    'Apply legal personality concepts to modern finance',
                    'Understand Shariah-compliant corporate structures',
                    'Evaluate case studies involving legal personality'
                ];

            case 'Agency and Partnership in Contemporary Context':
                return [
                    'Understand Wakalah (agency) in modern financial contexts',
                    'Apply Shirkah (partnership) principles to contemporary finance',
                    'Analyze Shariah-compliant agency agreements',
                    'Explore partnership models in Islamic finance',
                    'Evaluate real-world applications of agency and partnership'
                ];

            case 'Contracts and Guarantees in Islamic Finance':
                return [
                    'Master the principles of Islamic contracts',
                    'Understand guarantee systems in banking agreements',
                    'Analyze Shariah-compliant sales contracts',
                    'Address contemporary issues in Islamic sales',
                    'Evaluate compensation structures in Islamic finance'
                ];

            default:
                return [
                    'Master key concepts in Islamic finance',
                    'Apply Shariah principles to practical scenarios',
                    'Analyze real-world case studies in Islamic law',
                    'Stay updated with Shariah-compliant financial practices',
                    'Develop problem-solving skills for Islamic finance challenges'
                ];
        }
    }

    private function getRequirementFeatures($courseName)
    {
        switch ($courseName) {
            case 'Islamic Banking and Finance':
                return [
                    'No prior knowledge of Islamic finance required',
                    'Basic understanding of banking concepts helpful',
                    'Access to a computer with internet connection',
                    'Willingness to learn Shariah-compliant principles'
                ];

            case 'Modern Applications of Mudarabah and Murabaha':
                return [
                    'Basic understanding of Islamic finance principles',
                    'Familiarity with general financial concepts',
                    'Access to a computer with internet connection',
                    'Interest in investment and risk-sharing models'
                ];

            case 'Riba (Interest) and its Contemporary Forms':
                return [
                    'No prior knowledge of Riba required',
                    'Basic understanding of financial systems helpful',
                    'Access to a computer with internet connection',
                    'Interest in Shariah-compliant financial practices'
                ];

            case 'Investment Tools in Shariah':
                return [
                    'Basic knowledge of Islamic finance principles',
                    'Familiarity with investment concepts helpful',
                    'Access to a computer with internet connection',
                    'Logical thinking and problem-solving skills'
                ];

            case 'Legal Personality in Islamic Law':
                return [
                    'No prior knowledge of Islamic law required',
                    'Basic understanding of legal concepts helpful',
                    'Access to a computer with internet connection',
                    'Interest in Shariah-compliant legal structures'
                ];

            case 'Agency and Partnership in Contemporary Context':
                return [
                    'Basic knowledge of Islamic finance or law helpful',
                    'Familiarity with business partnerships recommended',
                    'Access to a computer with internet connection',
                    'Willingness to learn Shariah-compliant contracts'
                ];

            case 'Contracts and Guarantees in Islamic Finance':
                return [
                    'Basic understanding of Islamic finance principles',
                    'Familiarity with contract law helpful',
                    'Access to a computer with internet connection',
                    'Interest in Shariah-compliant financial agreements'
                ];

            default:
                return [
                    'Basic computer skills required',
                    'Willingness to learn and practice regularly',
                    'Internet connection for accessing course materials',
                    'No specific software is required to begin'
                ];
        }
    }

    private function getIncludeFeatures($courseName)
    {
        $lectureCount = DB::table('lectures')
            ->join('courses', 'lectures.course_id', '=', 'courses.id')
            ->where('courses.name', $courseName)
            ->count();

        $basicFeatures = [
            "$lectureCount on-demand video lectures",
            "Unlimited access",
            "Access on mobile and desktop",
            "Certificate of completion"
        ];

        switch ($courseName) {
            case 'Islamic Banking and Finance':
                return array_merge($basicFeatures, [
                    "Case studies on Shariah-compliant banking",
                    "Reference guides for Islamic banking principles",
                    "Access to course discussion forums"
                ]);

            case 'Modern Applications of Mudarabah and Murabaha':
                return array_merge($basicFeatures, [
                    "Practical examples of Mudarabah and Murabaha",
                    "Templates for Shariah-compliant contracts",
                    "Access to course community for collaboration"
                ]);

            case 'Riba (Interest) and its Contemporary Forms':
                return array_merge($basicFeatures, [
                    "Riba identification checklists",
                    "Shariah rulings reference materials",
                    "Case studies on avoiding Riba"
                ]);

            case 'Investment Tools in Shariah':
                return array_merge($basicFeatures, [
                    "Templates for Istisna and Salam contracts",
                    "Guides on Shariah-compliant mutual funds",
                    "Investment scenario case studies"
                ]);

            case 'Legal Personality in Islamic Law':
                return array_merge($basicFeatures, [
                    "Reference materials on Shariah legal structures",
                    "Case studies on legal personality applications",
                    "Access to course discussion forums"
                ]);

            case 'Agency and Partnership in Contemporary Context':
                return array_merge($basicFeatures, [
                    "Templates for Wakalah and Shirkah agreements",
                    "Case studies on agency and partnership",
                    "Practical exercises for contract drafting"
                ]);

            case 'Contracts and Guarantees in Islamic Finance':
                return array_merge($basicFeatures, [
                    "Sample Shariah-compliant contracts",
                    "Guides on guarantee systems",
                    "Case studies on modern sales issues"
                ]);

            default:
                return $basicFeatures;
        }
    }
}
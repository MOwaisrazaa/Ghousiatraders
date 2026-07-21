<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Lecture;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all lectures
        $lectures = Lecture::all();

        foreach ($lectures as $index => $lecture) {
            // Create quiz for this lecture
            $quiz = Quiz::create([
                'lecture_id' => $lecture->id,
                'title' => "Quiz for {$lecture->name}",
                'description' => "Test your knowledge of {$lecture->name}",
                'total_points' => 100,
                'passing_score' => 70,
                'is_required' => $index % 3 == 0, // Make some quizzes required
                'time_limit' => $index % 2 == 0 ? 15 : null, // Some quizzes have time limits
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Add multiple choice questions
            $this->createMultipleChoiceQuestions($quiz, $lecture->name);

            // Add open ended questions
            $this->createOpenEndedQuestions($quiz, $lecture->name);
        }
    }

    /**
     * Create multiple choice questions for a quiz
     */
    private function createMultipleChoiceQuestions(Quiz $quiz, string $lectureName)
    {
        $questions = $this->getMultipleChoiceQuestions($lectureName);

        foreach ($questions as $index => $questionData) {
            // Create the question
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question'],
                'question_type' => 'multiple_choice',
                'points' => $questionData['points'],
                'order' => $index + 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Create the options
            foreach ($questionData['options'] as $optionIndex => $optionData) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => $optionData['correct'],
                    'order' => $optionIndex + 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    /**
     * Create open ended questions for a quiz
     */
    private function createOpenEndedQuestions(Quiz $quiz, string $lectureName)
    {
        $questions = $this->getOpenEndedQuestions($lectureName);
        $startingOrder = QuizQuestion::where('quiz_id', $quiz->id)->count() + 1;

        foreach ($questions as $index => $questionData) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $questionData['question'],
                'question_type' => 'open_ended',
                'points' => $questionData['points'],
                'order' => $startingOrder + $index,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Get multiple choice questions based on lecture name
     */
    private function getMultipleChoiceQuestions(string $lectureName): array
    {
        switch ($lectureName) {
            // Islamic Banking and Finance
            case 'Currency Transactions in Islamic Banking':
                return [
                    [
                        'question' => 'Which principle governs permissible currency transactions in Islamic banking?',
                        'options' => [
                            ['text' => 'Riba prohibition', 'correct' => true],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Interest-based lending', 'correct' => false],
                            ['text' => 'Non-Shariah compliance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key requirement for currency exchange in Islamic finance?',
                        'options' => [
                            ['text' => 'Spot transaction', 'correct' => true],
                            ['text' => 'Deferred payment', 'correct' => false],
                            ['text' => 'Interest accrual', 'correct' => false],
                            ['text' => 'Speculative delay', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which practice is avoided in Shariah-compliant currency transactions?',
                        'options' => [
                            ['text' => 'Riba al-Fadl', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Asset-backed trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Dollar Booking and Shariah Compliance':
                return [
                    [
                        'question' => 'What ensures Shariah compliance in dollar booking?',
                        'options' => [
                            ['text' => 'Adherence to Shariah contracts', 'correct' => true],
                            ['text' => 'Interest-based booking', 'correct' => false],
                            ['text' => 'Speculative investments', 'correct' => false],
                            ['text' => 'Non-transparent transactions', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract is commonly used in Shariah-compliant dollar booking?',
                        'options' => [
                            ['text' => 'Murabaha', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar contract', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in dollar booking under Shariah?',
                        'options' => [
                            ['text' => 'Riba', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Asset backing', 'correct' => false],
                            ['text' => 'Transparency', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Letter of Credit (LC) Amount Collection':
                return [
                    [
                        'question' => 'What is a key Shariah requirement for LC amount collection?',
                        'options' => [
                            ['text' => 'No interest charges', 'correct' => true],
                            ['text' => 'Delayed payments', 'correct' => false],
                            ['text' => 'Speculative gains', 'correct' => false],
                            ['text' => 'Non-transparent fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract supports LC in Islamic finance?',
                        'options' => [
                            ['text' => 'Wakalah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Interest contract', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures compliance in LC collection?',
                        'options' => [
                            ['text' => 'Shariah-compliant contracts', 'correct' => true],
                            ['text' => 'Interest accumulation', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Non-disclosed fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Shariah Perspective on Bank Card Types':
                return [
                    [
                        'question' => 'Which feature makes a bank card Shariah-compliant?',
                        'options' => [
                            ['text' => 'No interest charges', 'correct' => true],
                            ['text' => 'High interest rates', 'correct' => false],
                            ['text' => 'Speculative rewards', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in Shariah-compliant bank cards?',
                        'options' => [
                            ['text' => 'Riba', 'correct' => true],
                            ['text' => 'Cashback rewards', 'correct' => false],
                            ['text' => 'Transparent fees', 'correct' => false],
                            ['text' => 'Asset-backed payments', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract supports Shariah-compliant cards?',
                        'options' => [
                            ['text' => 'Ijarah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Treasury of Islamic Banks':
                return [
                    [
                        'question' => 'What is a key function of an Islamic bank’s treasury?',
                        'options' => [
                            ['text' => 'Liquidity management', 'correct' => true],
                            ['text' => 'Interest-based lending', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-Shariah investments', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which principle guides treasury operations?',
                        'options' => [
                            ['text' => 'Shariah compliance', 'correct' => true],
                            ['text' => 'Interest accumulation', 'correct' => false],
                            ['text' => 'Speculative gains', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What contract is used in treasury operations?',
                        'options' => [
                            ['text' => 'Mudarabah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar contract', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Modern Applications of Mudarabah and Murabaha
            case 'Introduction to Mudarabah':
                return [
                    [
                        'question' => 'What defines a Mudarabah contract?',
                        'options' => [
                            ['text' => 'Profit-sharing partnership', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Fixed repayment', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Who bears the loss in a Mudarabah contract?',
                        'options' => [
                            ['text' => 'Capital provider', 'correct' => true],
                            ['text' => 'Entrepreneur', 'correct' => false],
                            ['text' => 'Both equally', 'correct' => false],
                            ['text' => 'Third party', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key feature of Mudarabah?',
                        'options' => [
                            ['text' => 'Risk-sharing', 'correct' => true],
                            ['text' => 'Fixed interest', 'correct' => false],
                            ['text' => 'Speculative gains', 'correct' => false],
                            ['text' => 'Non-transparent terms', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Use of Mudarabah in Contemporary Investment':
                return [
                    [
                        'question' => 'How is Mudarabah applied in modern investments?',
                        'options' => [
                            ['text' => 'Profit-sharing ventures', 'correct' => true],
                            ['text' => 'Interest-based loans', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Fixed repayment schemes', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Mudarabah compliance in investments?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Non-disclosed profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector commonly uses Mudarabah?',
                        'options' => [
                            ['text' => 'Islamic banking', 'correct' => true],
                            ['text' => 'Conventional lending', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Introduction to Murabaha':
                return [
                    [
                        'question' => 'What defines a Murabaha contract?',
                        'options' => [
                            ['text' => 'Cost-plus financing', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key feature of Murabaha?',
                        'options' => [
                            ['text' => 'Transparent pricing', 'correct' => true],
                            ['text' => 'Hidden fees', 'correct' => false],
                            ['text' => 'Speculative profits', 'correct' => false],
                            ['text' => 'Interest charges', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Who bears the risk in Murabaha?',
                        'options' => [
                            ['text' => 'Seller until delivery', 'correct' => true],
                            ['text' => 'Buyer exclusively', 'correct' => false],
                            ['text' => 'Both equally', 'correct' => false],
                            ['text' => 'Third party', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Use of Murabaha in Contemporary Investment':
                return [
                    [
                        'question' => 'How is Murabaha used in modern finance?',
                        'options' => [
                            ['text' => 'Asset financing', 'correct' => true],
                            ['text' => 'Interest-based loans', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Murabaha compliance?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden costs', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector uses Murabaha frequently?',
                        'options' => [
                            ['text' => 'Islamic banking', 'correct' => true],
                            ['text' => 'Conventional lending', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Riba (Interest) and its Contemporary Forms
            case 'Definition of Riba':
                return [
                    [
                        'question' => 'What is Riba in Islamic finance?',
                        'options' => [
                            ['text' => 'Prohibited interest', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Asset-backed trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which source prohibits Riba?',
                        'options' => [
                            ['text' => 'Quran and Sunnah', 'correct' => true],
                            ['text' => 'Modern banking laws', 'correct' => false],
                            ['text' => 'Secular regulations', 'correct' => false],
                            ['text' => 'Cultural traditions', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a consequence of Riba?',
                        'options' => [
                            ['text' => 'Economic injustice', 'correct' => true],
                            ['text' => 'Wealth distribution', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Transparent profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Types of Riba':
                return [
                    [
                        'question' => 'Which type of Riba involves excess in exchange?',
                        'options' => [
                            ['text' => 'Riba al-Fadl', 'correct' => true],
                            ['text' => 'Riba al-Nasi’ah', 'correct' => false],
                            ['text' => 'Riba al-Qard', 'correct' => false],
                            ['text' => 'Riba al-Maysir', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is Riba al-Nasi’ah related to?',
                        'options' => [
                            ['text' => 'Delayed payments', 'correct' => true],
                            ['text' => 'Excess in exchange', 'correct' => false],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Asset trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which items are subject to Riba al-Fadl?',
                        'options' => [
                            ['text' => 'Gold and silver', 'correct' => true],
                            ['text' => 'Real estate', 'correct' => false],
                            ['text' => 'Services', 'correct' => false],
                            ['text' => 'Stocks', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Examples of Riba in the Modern Era':
                return [
                    [
                        'question' => 'Which modern practice involves Riba?',
                        'options' => [
                            ['text' => 'Interest-based loans', 'correct' => true],
                            ['text' => 'Profit-sharing ventures', 'correct' => false],
                            ['text' => 'Asset-backed financing', 'correct' => false],
                            ['text' => 'Risk-sharing contracts', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a common example of Riba in banking?',
                        'options' => [
                            ['text' => 'Credit card interest', 'correct' => true],
                            ['text' => 'Murabaha financing', 'correct' => false],
                            ['text' => 'Mudarabah investments', 'correct' => false],
                            ['text' => 'Sukuk issuance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'How can Riba be avoided in modern finance?',
                        'options' => [
                            ['text' => 'Shariah-compliant contracts', 'correct' => true],
                            ['text' => 'Interest-based loans', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Shariah Rulings on Riba':
                return [
                    [
                        'question' => 'What does Shariah say about Riba?',
                        'options' => [
                            ['text' => 'Strictly prohibited', 'correct' => true],
                            ['text' => 'Permitted with conditions', 'correct' => false],
                            ['text' => 'Encouraged in trade', 'correct' => false],
                            ['text' => 'Optional practice', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which source provides rulings on Riba?',
                        'options' => [
                            ['text' => 'Quran and Hadith', 'correct' => true],
                            ['text' => 'Modern banking laws', 'correct' => false],
                            ['text' => 'Secular regulations', 'correct' => false],
                            ['text' => 'Cultural norms', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key ruling to avoid Riba?',
                        'options' => [
                            ['text' => 'Use Shariah contracts', 'correct' => true],
                            ['text' => 'Charge interest', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Investment Tools in Shariah
            case 'Istisna Contract and Its Role':
                return [
                    [
                        'question' => 'What is the purpose of an Istisna contract?',
                        'options' => [
                            ['text' => 'Manufacturing financing', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Profit-sharing', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Istisna compliance?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden costs', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector uses Istisna frequently?',
                        'options' => [
                            ['text' => 'Construction', 'correct' => true],
                            ['text' => 'Conventional lending', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Salam Contract as a Business Solution':
                return [
                    [
                        'question' => 'What defines a Salam contract?',
                        'options' => [
                            ['text' => 'Advance payment for goods', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key feature of Salam contracts?',
                        'options' => [
                            ['text' => 'Full advance payment', 'correct' => true],
                            ['text' => 'Deferred payment', 'correct' => false],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative delivery', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector benefits from Salam contracts?',
                        'options' => [
                            ['text' => 'Agriculture', 'correct' => true],
                            ['text' => 'Conventional banking', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'What are Mutual Fund Institutions?':
                return [
                    [
                        'question' => 'What defines a Shariah-compliant mutual fund?',
                        'options' => [
                            ['text' => 'Shariah-screened investments', 'correct' => true],
                            ['text' => 'Interest-based returns', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures mutual fund compliance?',
                        'options' => [
                            ['text' => 'Shariah board oversight', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Who manages mutual fund investments?',
                        'options' => [
                            ['text' => 'Fund manager', 'correct' => true],
                            ['text' => 'Individual investor', 'correct' => false],
                            ['text' => 'Bank teller', 'correct' => false],
                            ['text' => 'Government agency', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'How Mutual Funds Work in Shariah':
                return [
                    [
                        'question' => 'What is a key feature of Shariah mutual funds?',
                        'options' => [
                            ['text' => 'Profit-sharing', 'correct' => true],
                            ['text' => 'Interest-based returns', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'How are profits distributed in Shariah funds?',
                        'options' => [
                            ['text' => 'Based on Shariah contracts', 'correct' => true],
                            ['text' => 'Fixed interest rates', 'correct' => false],
                            ['text' => 'Speculative gains', 'correct' => false],
                            ['text' => 'Non-disclosed methods', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Shariah compliance in funds?',
                        'options' => [
                            ['text' => 'Shariah screening', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative investments', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Legal Personality in Islamic Law
            case 'Concept of Legal Person':
                return [
                    [
                        'question' => 'What defines a legal person in Shariah?',
                        'options' => [
                            ['text' => 'Entity with legal rights', 'correct' => true],
                            ['text' => 'Individual only', 'correct' => false],
                            ['text' => 'Non-Shariah entity', 'correct' => false],
                            ['text' => 'Speculative body', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which entities can be legal persons?',
                        'options' => [
                            ['text' => 'Corporations', 'correct' => true],
                            ['text' => 'Non-Shariah firms', 'correct' => false],
                            ['text' => 'Speculative ventures', 'correct' => false],
                            ['text' => 'Unregistered entities', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What supports legal personality in Shariah?',
                        'options' => [
                            ['text' => 'Shariah principles', 'correct' => true],
                            ['text' => 'Interest-based laws', 'correct' => false],
                            ['text' => 'Secular regulations', 'correct' => false],
                            ['text' => 'Cultural norms', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Shariah Status of Legal Persons':
                return [
                    [
                        'question' => 'How are legal persons recognized in Shariah?',
                        'options' => [
                            ['text' => 'Through Shariah contracts', 'correct' => true],
                            ['text' => 'Interest-based agreements', 'correct' => false],
                            ['text' => 'Speculative entities', 'correct' => false],
                            ['text' => 'Non-Shariah laws', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is a key feature of legal persons?',
                        'options' => [
                            ['text' => 'Contractual capacity', 'correct' => true],
                            ['text' => 'Interest liability', 'correct' => false],
                            ['text' => 'Speculative status', 'correct' => false],
                            ['text' => 'Non-transparent roles', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Shariah compliance for legal persons?',
                        'options' => [
                            ['text' => 'Shariah governance', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Secular laws', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Applications in Islamic Finance':
                return [
                    [
                        'question' => 'How is legal personality applied in Islamic finance?',
                        'options' => [
                            ['text' => 'Corporate financing', 'correct' => true],
                            ['text' => 'Interest-based loans', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-Shariah deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract supports legal personality in finance?',
                        'options' => [
                            ['text' => 'Mudarabah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures compliance in corporate financing?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Agency and Partnership in Contemporary Context
            case 'Agency in Contemporary Issues':
                return [
                    [
                        'question' => 'What defines Wakalah in modern finance?',
                        'options' => [
                            ['text' => 'Agency contract', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Profit-sharing', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Wakalah compliance?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector uses Wakalah frequently?',
                        'options' => [
                            ['text' => 'Islamic banking', 'correct' => true],
                            ['text' => 'Conventional lending', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Partnership in Contemporary Issues':
                return [
                    [
                        'question' => 'What defines Shirkah in modern finance?',
                        'options' => [
                            ['text' => 'Partnership contract', 'correct' => true],
                            ['text' => 'Interest-based loan', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Fixed repayment', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Shirkah compliance?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which sector uses Shirkah frequently?',
                        'options' => [
                            ['text' => 'Islamic banking', 'correct' => true],
                            ['text' => 'Conventional lending', 'correct' => false],
                            ['text' => 'Speculative markets', 'correct' => false],
                            ['text' => 'Non-Shariah finance', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Principles of Agency in Shariah':
                return [
                    [
                        'question' => 'What is a key principle of Wakalah?',
                        'options' => [
                            ['text' => 'Agent acts on behalf of principal', 'correct' => true],
                            ['text' => 'Interest-based payments', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Wakalah compliance in Shariah?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative agreements', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in Wakalah contracts?',
                        'options' => [
                            ['text' => 'Riba', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Transparency', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Principles of Partnership in Shariah':
                return [
                    [
                        'question' => 'What is a key principle of Shirkah?',
                        'options' => [
                            ['text' => 'Profit and loss sharing', 'correct' => true],
                            ['text' => 'Interest-based returns', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Fixed repayments', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures Shirkah compliance in Shariah?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden profits', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in Shirkah contracts?',
                        'options' => [
                            ['text' => 'Riba', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Transparency', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            // Contracts and Guarantees in Islamic Finance
            case 'Services and Their Compensation':
                return [
                    [
                        'question' => 'What defines compensation in Islamic service contracts?',
                        'options' => [
                            ['text' => 'Agreed-upon fees', 'correct' => true],
                            ['text' => 'Interest-based payments', 'correct' => false],
                            ['text' => 'Speculative gains', 'correct' => false],
                            ['text' => 'Non-transparent fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract supports service compensation?',
                        'options' => [
                            ['text' => 'Ijarah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures compliance in service contracts?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Guarantee System in Bank Agreements':
                return [
                    [
                        'question' => 'What defines a guarantee in Islamic banking?',
                        'options' => [
                            ['text' => 'Shariah-compliant security', 'correct' => true],
                            ['text' => 'Interest-based collateral', 'correct' => false],
                            ['text' => 'Speculative backing', 'correct' => false],
                            ['text' => 'Non-transparent terms', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract supports guarantees?',
                        'options' => [
                            ['text' => 'Kafalah', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures compliance in guarantees?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Basic Rulings of Sales':
                return [
                    [
                        'question' => 'What is a key principle of Islamic sales?',
                        'options' => [
                            ['text' => 'Transparency', 'correct' => true],
                            ['text' => 'Interest charges', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden costs', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract governs Islamic sales?',
                        'options' => [
                            ['text' => 'Bay’ (sale)', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in Islamic sales?',
                        'options' => [
                            ['text' => 'Gharar', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Transparency', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            case 'Contemporary Issues in Sales':
                return [
                    [
                        'question' => 'What is a modern challenge in Islamic sales?',
                        'options' => [
                            ['text' => 'Ensuring Shariah compliance', 'correct' => true],
                            ['text' => 'Interest-based financing', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract addresses modern sales issues?',
                        'options' => [
                            ['text' => 'Murabaha', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What ensures compliance in modern sales?',
                        'options' => [
                            ['text' => 'Shariah guidelines', 'correct' => true],
                            ['text' => 'Interest inclusion', 'correct' => false],
                            ['text' => 'Speculative terms', 'correct' => false],
                            ['text' => 'Hidden fees', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];

            default:
                return [
                    [
                        'question' => 'Which principle is key in Islamic finance?',
                        'options' => [
                            ['text' => 'Shariah compliance', 'correct' => true],
                            ['text' => 'Interest-based transactions', 'correct' => false],
                            ['text' => 'Speculative trading', 'correct' => false],
                            ['text' => 'Non-transparent deals', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'What is prohibited in Islamic finance?',
                        'options' => [
                            ['text' => 'Riba', 'correct' => true],
                            ['text' => 'Profit-sharing', 'correct' => false],
                            ['text' => 'Risk-sharing', 'correct' => false],
                            ['text' => 'Asset-backed trading', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                    [
                        'question' => 'Which contract is commonly used in Islamic finance?',
                        'options' => [
                            ['text' => 'Murabaha', 'correct' => true],
                            ['text' => 'Riba-based loan', 'correct' => false],
                            ['text' => 'Gharar agreement', 'correct' => false],
                            ['text' => 'Speculative futures', 'correct' => false],
                        ],
                        'points' => 20,
                    ],
                ];
        }
    }

    /**
     * Get open-ended questions based on lecture name
     */
    private function getOpenEndedQuestions(string $lectureName): array
    {
        switch ($lectureName) {
            // Islamic Banking and Finance
            case 'Currency Transactions in Islamic Banking':
                return [
                    [
                        'question' => 'Explain how Shariah-compliant currency transactions differ from conventional ones.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical application of currency exchange in Islamic banking.',
                        'points' => 20,
                    ],
                ];

            case 'Dollar Booking and Shariah Compliance':
                return [
                    [
                        'question' => 'Explain the Shariah requirements for dollar booking in Islamic banks.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where dollar booking ensures Shariah compliance.',
                        'points' => 20,
                    ],
                ];

            case 'Letter of Credit (LC) Amount Collection':
                return [
                    [
                        'question' => 'Explain the Shariah perspective on LC amount collection.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a case where LC collection aligns with Shariah principles.',
                        'points' => 20,
                    ],
                ];

            case 'Shariah Perspective on Bank Card Types':
                return [
                    [
                        'question' => 'Explain how bank cards can be made Shariah-compliant.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where a bank card avoids Riba.',
                        'points' => 20,
                    ],
                ];

            case 'Treasury of Islamic Banks':
                return [
                    [
                        'question' => 'Explain the role of treasury operations in Islamic banks.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a Shariah-compliant treasury strategy.',
                        'points' => 20,
                    ],
                ];

            // Modern Applications of Mudarabah and Murabaha
            case 'Introduction to Mudarabah':
                return [
                    [
                        'question' => 'Explain the core principles of a Mudarabah contract.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where Mudarabah is used in finance.',
                        'points' => 20,
                    ],
                ];

            case 'Use of Mudarabah in Contemporary Investment':
                return [
                    [
                        'question' => 'Explain how Mudarabah is applied in modern investments.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical example of a Mudarabah investment.',
                        'points' => 20,
                    ],
                ];

            case 'Introduction to Murabaha':
                return [
                    [
                        'question' => 'Explain the structure of a Murabaha contract.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where Murabaha is used in financing.',
                        'points' => 20,
                    ],
                ];

            case 'Use of Murabaha in Contemporary Investment':
                return [
                    [
                        'question' => 'Explain how Murabaha is used in modern finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical example of a Murabaha transaction.',
                        'points' => 20,
                    ],
                ];

            // Riba (Interest) and its Contemporary Forms
            case 'Definition of Riba':
                return [
                    [
                        'question' => 'Explain the concept of Riba in Islamic finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe why Riba is prohibited in Shariah.',
                        'points' => 20,
                    ],
                ];

            case 'Types of Riba':
                return [
                    [
                        'question' => 'Explain the differences between Riba al-Fadl and Riba al-Nasi’ah.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe an example of each type of Riba.',
                        'points' => 20,
                    ],
                ];

            case 'Examples of Riba in the Modern Era':
                return [
                    [
                        'question' => 'Explain how Riba appears in modern financial systems.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a strategy to avoid Riba in banking.',
                        'points' => 20,
                    ],
                ];

            case 'Shariah Rulings on Riba':
                return [
                    [
                        'question' => 'Explain the Shariah rulings on Riba.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where Riba is avoided using Shariah principles.',
                        'points' => 20,
                    ],
                ];

            // Investment Tools in Shariah
            case 'Istisna Contract and Its Role':
                return [
                    [
                        'question' => 'Explain the structure of an Istisna contract.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical application of Istisna in investment.',
                        'points' => 20,
                    ],
                ];

            case 'Salam Contract as a Business Solution':
                return [
                    [
                        'question' => 'Explain how a Salam contract works in Islamic finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a business scenario where Salam is used.',
                        'points' => 20,
                    ],
                ];

            case 'What are Mutual Fund Institutions?':
                return [
                    [
                        'question' => 'Explain the role of mutual funds in Islamic finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a Shariah-compliant mutual fund structure.',
                        'points' => 20,
                    ],
                ];

            case 'How Mutual Funds Work in Shariah':
                return [
                    [
                        'question' => 'Explain the operational mechanics of Shariah-compliant mutual funds.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe how profits are distributed in Shariah mutual funds.',
                        'points' => 20,
                    ],
                ];

            // Legal Personality in Islamic Law
            case 'Concept of Legal Person':
                return [
                    [
                        'question' => 'Explain the concept of legal personality in Shariah.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe an example of a legal person in Islamic law.',
                        'points' => 20,
                    ],
                ];

            case 'Shariah Status of Legal Persons':
                return [
                    [
                        'question' => 'Explain how legal persons are recognized in Shariah.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where legal personality is applied.',
                        'points' => 20,
                    ],
                ];

            case 'Applications in Islamic Finance':
                return [
                    [
                        'question' => 'Explain how legal personality is applied in Islamic finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical example of legal personality in finance.',
                        'points' => 20,
                    ],
                ];

            // Agency and Partnership in Contemporary Context
            case 'Agency in Contemporary Issues':
                return [
                    [
                        'question' => 'Explain how Wakalah is used in modern finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where Wakalah is applied.',
                        'points' => 20,
                    ],
                ];

            case 'Partnership in Contemporary Issues':
                return [
                    [
                        'question' => 'Explain how Shirkah is used in modern finance.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where Shirkah is applied.',
                        'points' => 20,
                    ],
                ];

            case 'Principles of Agency in Shariah':
                return [
                    [
                        'question' => 'Explain the core principles of Wakalah in Shariah.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe an example of a Wakalah contract.',
                        'points' => 20,
                    ],
                ];

            case 'Principles of Partnership in Shariah':
                return [
                    [
                        'question' => 'Explain the core principles of Shirkah in Shariah.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe an example of a Shirkah contract.',
                        'points' => 20,
                    ],
                ];

            // Contracts and Guarantees in Islamic Finance
            case 'Services and Their Compensation':
                return [
                    [
                        'question' => 'Explain how compensation is structured in Islamic service contracts.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario involving Shariah-compliant service compensation.',
                        'points' => 20,
                    ],
                ];

            case 'Guarantee System in Bank Agreements':
                return [
                    [
                        'question' => 'Explain the structure of guarantees in Islamic banking.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a scenario where a guarantee is used in banking.',
                        'points' => 20,
                    ],
                ];

            case 'Basic Rulings of Sales':
                return [
                    [
                        'question' => 'Explain the fundamental principles of sales in Islamic law.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe an example of a Shariah-compliant sale.',
                        'points' => 20,
                    ],
                ];

            case 'Contemporary Issues in Sales':
                return [
                    [
                        'question' => 'Explain a modern challenge in Islamic sales contracts.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a strategy to address a sales issue in Shariah.',
                        'points' => 20,
                    ],
                ];

            default:
                return [
                    [
                        'question' => 'Explain a key concept in Islamic finance from this lecture.',
                        'points' => 20,
                    ],
                    [
                        'question' => 'Describe a practical application of the lecture’s content.',
                        'points' => 20,
                    ],
                ];
        }
    }
}
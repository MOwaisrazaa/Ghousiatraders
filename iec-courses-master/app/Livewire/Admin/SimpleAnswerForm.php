<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Question;
use App\Models\Answer;
use App\Models\AnswerAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SimpleAnswerForm extends Component
{
    use WithFileUploads;

    public $questionId;
    public $question;
    public $content = '';
    public $isPinned = false;
    public $image;
    public $pdf;
    public $voiceData;

    protected $rules = [
        'content' => 'required|string|min:10',
        'image' => 'nullable|image|max:2048',
        'pdf' => 'nullable|mimes:pdf|max:5120',
        'voiceData' => 'nullable|string',
    ];

    protected $messages = [
        'content.required' => 'Please provide an answer.',
        'content.min' => 'Answer must be at least 10 characters long.',
        'image.image' => 'Please upload a valid image file.',
        'image.max' => 'Image size must not exceed 2MB.',
        'pdf.mimes' => 'Please upload a valid PDF file.',
        'pdf.max' => 'PDF size must not exceed 5MB.',
    ];

    public function mount($questionId)
    {
        $this->questionId = $questionId;
        $this->question = Question::findOrFail($questionId);
    }

    public function submitAnswer()
    {
        $this->validate();

        try {
            // Create the answer using mass assignment
            $answer = Answer::create([
                'question_id' => $this->questionId,
                'user_id' => Auth::id(),
                'content' => $this->content,
                'is_pinned' => $this->isPinned,
                'is_accepted' => true, // Admin answers are automatically accepted
            ]);

            \Log::info('Answer created successfully', [
                'answer_id' => $answer->id,
                'question_id' => $this->questionId,
                'user_id' => Auth::id(),
                'has_voice_data' => !empty($this->voiceData)
            ]);

            // Handle image upload as attachment
            if ($this->image) {
                $imagePath = $this->image->store('answer_attachments/images', 'public');

                AnswerAttachment::create([
                    'answer_id' => $answer->id,
                    'file_path' => $imagePath,
                    'original_name' => $this->image->getClientOriginalName(),
                    'file_type' => 'image',
                    'file_size' => $this->image->getSize(),
                ]);

                \Log::info("Image attachment saved: {$imagePath}");
            }

            // Handle PDF upload as attachment
            if ($this->pdf) {
                $pdfPath = $this->pdf->store('answer_attachments/pdfs', 'public');

                AnswerAttachment::create([
                    'answer_id' => $answer->id,
                    'file_path' => $pdfPath,
                    'original_name' => $this->pdf->getClientOriginalName(),
                    'file_type' => 'pdf',
                    'file_size' => $this->pdf->getSize(),
                ]);

                \Log::info("PDF attachment saved: {$pdfPath}");
            }

            // Handle voice recording as attachment
            if ($this->voiceData) {
                \Log::info("Processing voice data for answer ID: {$answer->id}");

                // Extract the actual base64 data from the data URL
                $base64Data = $this->voiceData;
                if (Str::startsWith($base64Data, 'data:audio')) {
                    // Extract the audio type from the data URL
                    $matches = [];
                    preg_match('/data:audio\/(.*?);base64,/', $base64Data, $matches);
                    $audioType = $matches[1] ?? 'webm';

                    // Extract the actual base64 content
                    $base64Content = explode(',', $base64Data)[1] ?? '';

                    if (!empty($base64Content)) {
                        // Decode the base64 data
                        $decodedData = base64_decode($base64Content);

                        // Generate filename
                        $voiceFileName = 'voice_' . Str::random(10) . '_' . time() . '.' . $audioType;
                        $voicePath = 'answer_attachments/voice/' . $voiceFileName;

                        // Ensure the voice directory exists
                        if (!Storage::disk('public')->exists('answer_attachments/voice')) {
                            Storage::disk('public')->makeDirectory('answer_attachments/voice');
                        }

                        // Store the voice file
                        $stored = Storage::disk('public')->put($voicePath, $decodedData);

                        if ($stored) {
                            AnswerAttachment::create([
                                'answer_id' => $answer->id,
                                'file_path' => $voicePath,
                                'original_name' => $voiceFileName,
                                'file_type' => 'audio',
                                'file_size' => strlen($decodedData),
                            ]);

                            \Log::info("Voice attachment saved: {$voicePath}");
                        } else {
                            \Log::error("Failed to store voice file: {$voicePath}");
                        }
                    }
                }
            }

            // Update question status to approved
            $question = Question::findOrFail($this->questionId);
            $question->status = 'approved';
            $question->save();
            \Log::info("Question {$question->id} status updated to approved");

            // Reset form
            $this->reset(['content', 'isPinned', 'image', 'pdf', 'voiceData']);

            // Emit success event
            $this->dispatch('answer-submitted', [
                'message' => 'Answer submitted successfully!',
                'questionId' => $this->questionId
            ]);

            // Close modal
            $this->dispatch('close-modal', ['modalId' => "answerModal{$this->questionId}"]);

            // Show success message and redirect
            session()->flash('success', 'Answer submitted successfully!');

            // Use Livewire's redirect method instead of Laravel's
            $this->redirect(route('admin.questions.index'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Error submitting answer: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            session()->flash('error', 'Failed to submit answer: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.simple-answer-form');
    }
}

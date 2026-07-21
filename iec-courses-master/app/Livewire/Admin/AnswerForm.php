<?php

namespace App\Livewire\Admin;

use App\Models\Answer;
use App\Models\AnswerAttachment;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AnswerForm extends Component
{
    use WithFileUploads;

    public $questionId;
    public $content;
    public $isPinned = false;
    public $image;
    public $pdf;
    public $voiceData = null;
    public $recordingStatus = 'idle'; // idle, recording, recorded

    // Update listeners for Livewire 3
    protected function getListeners()
    {
        return [
            'setVoiceData',
            'recording-status-changed' => 'updateRecordingStatus'
        ];
    }

    public function updateRecordingStatus($data)
    {
        if (isset($data['status']) && isset($data['questionId']) && $data['questionId'] == $this->questionId) {
            $this->recordingStatus = $data['status'];
            \Log::info("Recording status updated via event for question {$this->questionId}: {$data['status']}");
        }
    }

    public function startRecording()
    {
        $this->recordingStatus = 'recording';
        \Log::info("Recording started for question {$this->questionId}");
    }

    public function stopRecording()
    {
        $this->recordingStatus = 'recorded';
        \Log::info("Recording stopped for question {$this->questionId}");
    }

    public function resetRecording()
    {
        $this->recordingStatus = 'idle';
        $this->voiceData = null;
        \Log::info("Recording reset for question {$this->questionId}");
    }

    // This property will make questionId accessible to JavaScript
    protected function getPublicProperties()
    {
        return [
            'questionId' => $this->questionId,
        ];
    }

    protected function rules()
    {
        return [
            'content' => 'required|string|min:5',
            'image' => 'nullable|image|max:5120', // 5MB limit
            'pdf' => 'nullable|mimes:pdf|max:10240', // 10MB limit
            'voiceData' => 'nullable|string'
        ];
    }

    public function mount($questionId)
    {
        $this->questionId = $questionId;
    }

    public function updatedVoiceData($value)
    {
        \Log::info('voiceData property updated', [
            'length' => $value ? strlen($value) : 0,
            'starts_with' => $value ? substr($value, 0, 30) . '...' : 'null'
        ]);

        if ($value) {
            $this->recordingStatus = 'recorded';
        }
    }

    public function setVoiceData($base64Data)
    {
        try {
            // Keep the full base64 string including data URI
            $this->voiceData = $base64Data;
            $this->recordingStatus = 'recorded';

            // Log for debugging
            \Log::info('setVoiceData method called: ' . (strlen($base64Data) > 100 ?
                substr($base64Data, 0, 100) . '... [' . strlen($base64Data) . ' chars total]' :
                $base64Data));

            // Test save to make sure storage is working
            if (Str::startsWith($base64Data, 'data:audio')) {
                // Extract the audio type from the data URL
                $matches = [];
                preg_match('/data:audio\/(.*?);base64,/', $base64Data, $matches);
                $audioType = $matches[1] ?? 'webm';

                // Extract the actual base64 content
                $base64Content = explode(',', $base64Data)[1] ?? '';

                // Test if we can decode the base64 data
                $decodedData = base64_decode($base64Content);
                $decodedSize = strlen($decodedData);

                // Log the decoded size
                \Log::info("Decoded audio data size: {$decodedSize} bytes, type: {$audioType}");

                // Test storage by writing to a temporary file
                $tempFilename = 'test_voice_' . time() . '.' . $audioType;
                $tempPath = 'temp/' . $tempFilename;

                try {
                    // Ensure the temp directory exists
                    if (!Storage::disk('public')->exists('temp')) {
                        Storage::disk('public')->makeDirectory('temp');
                    }

                    // Try to store the file
                    $stored = Storage::disk('public')->put($tempPath, $decodedData);
                    \Log::info("Test file storage result: " . ($stored ? 'Success' : 'Failed') .
                        " for path: {$tempPath}");

                    if ($stored) {
                        // Success - we'll use the actual path when submitting
                        \Log::info("Test file URL: " . Storage::disk('public')->url($tempPath));
                    }
                } catch (\Exception $e) {
                    \Log::error("Error in test storage: " . $e->getMessage());
                }
            } else {
                \Log::warning("Voice data doesn't start with data:audio - not valid format");
            }
        } catch (\Exception $e) {
            \Log::error("Error in setVoiceData: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }



    public function submitAnswer()
    {
        $this->validate();

        try {
            $question = Question::findOrFail($this->questionId);

            \Log::info('Submitting answer with voice data', [
                'has_voice_data' => !empty($this->voiceData),
                'voice_data_length' => $this->voiceData ? strlen($this->voiceData) : 0,
                'question_id' => $this->questionId,
                'recording_status' => $this->recordingStatus
            ]);

            // Create the answer
            $answer = Answer::create([
                'question_id' => $question->id,
                'user_id' => Auth::id(),
                'content' => $this->content,
                'is_pinned' => $this->isPinned,
                'is_accepted' => true,
            ]);

            \Log::info("Answer created successfully with ID: {$answer->id}");

            \Log::info("Answer created with ID: {$answer->id}");

            // Handle image attachment
            if ($this->image) {
                $path = $this->image->store('answer_attachments/images', 'public');

                AnswerAttachment::create([
                    'answer_id' => $answer->id,
                    'file_path' => $path,
                    'original_name' => $this->image->getClientOriginalName(),
                    'file_type' => 'image',
                    'file_size' => $this->image->getSize(),
                ]);

                \Log::info("Image attachment saved: {$path}");
            }

            // Handle PDF attachment
            if ($this->pdf) {
                $path = $this->pdf->store('answer_attachments/pdfs', 'public');

                AnswerAttachment::create([
                    'answer_id' => $answer->id,
                    'file_path' => $path,
                    'original_name' => $this->pdf->getClientOriginalName(),
                    'file_type' => 'pdf',
                    'file_size' => $this->pdf->getSize(),
                ]);

                \Log::info("PDF attachment saved: {$path}");
            }

            // Handle voice recording (base64 data)
            if ($this->voiceData) {
                \Log::info("Processing voice data for answer ID: {$answer->id}");

                // Extract the actual base64 data from the data URL
                $base64Data = $this->voiceData;

                if (Str::startsWith($base64Data, 'data:audio')) {
                    // Extract the audio type from the data URL
                    $matches = [];
                    preg_match('/data:audio\/(.*?);base64,/', $base64Data, $matches);
                    $audioType = $matches[1] ?? 'webm';

                    \Log::info("Audio type detected: {$audioType}");

                    // Extract the actual base64 content
                    $base64Data = explode(',', $base64Data)[1];

                    if (empty($base64Data)) {
                        \Log::error("Base64 content extraction failed - empty result");
                    } else {
                        \Log::info("Base64 content extracted, length: " . strlen($base64Data));

                        // Decode the base64 data
                        $decodedData = base64_decode($base64Data);

                        if ($decodedData === false) {
                            \Log::error("Base64 decoding failed");
                        } else {
                            $decodedSize = strlen($decodedData);
                            \Log::info("Decoded audio size: {$decodedSize} bytes");

                            if ($decodedSize > 0) {
                                // Generate a filename with the correct extension
                                $filename = 'voice_recording_' . time() . '.' . $audioType;
                                $path = 'answer_attachments/voice/' . $filename;

                                // Make sure directory exists
                                $directory = 'answer_attachments/voice';
                                if (!Storage::disk('public')->exists($directory)) {
                                    Storage::disk('public')->makeDirectory($directory);
                                }

                                // Store the file
                                $stored = Storage::disk('public')->put($path, $decodedData);

                                if ($stored) {
                                    \Log::info("Voice file saved successfully at: {$path}");

                                    // Create attachment record
                                    $attachment = AnswerAttachment::create([
                                        'answer_id' => $answer->id,
                                        'file_path' => $path,
                                        'original_name' => $filename,
                                        'file_type' => 'voice',
                                        'file_size' => $decodedSize,
                                        'mime_type' => 'audio/' . $audioType,
                                    ]);

                                    \Log::info("Voice attachment record created with ID: {$attachment->id}");
                                } else {
                                    \Log::error("Failed to save voice file to {$path}");
                                }
                            } else {
                                \Log::error("Decoded data is empty");
                            }
                        }
                    }
                } else {
                    \Log::error("Voice data is not in expected format. Should start with 'data:audio'");
                }
            } else {
                \Log::info("No voice data to process");
            }

            // Update question status to approved
            $question->status = 'approved';
            $question->save();
            \Log::info("Question {$question->id} status updated to approved");

            // Reset form
            $this->reset(['content', 'isPinned', 'image', 'pdf', 'voiceData', 'recordingStatus']);

            // Show success notification
            session()->flash('success', 'Answer submitted successfully!');

            // Redirect to refresh the page
            return redirect()->route('admin.questions.index');

        } catch (\Exception $e) {
            \Log::error("Error in submitAnswer: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            session()->flash('error', 'Error submitting answer: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Log the current state
        if ($this->recordingStatus === 'recorded' && $this->voiceData) {
            \Log::info('Rendering form with voice data: ' . substr($this->voiceData, 0, 50) . '... [' . strlen($this->voiceData) . ' chars]');
        }

        return view('livewire.admin.answer-form');
    }
}

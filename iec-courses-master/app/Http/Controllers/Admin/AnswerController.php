<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\AdminUserAssignment;
use App\Models\AnswerAttachment;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of the pending questions.
     */
    public function index()
    {
        $user = Auth::user();

        // If super admin, show all pending questions
        if ($user->isSuperAdmin()) {
            $pendingQuestions = Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular admin only sees questions from their assigned users
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            $pendingQuestions = Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->whereIn('user_id', $assignedUserIds)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.questions.index', compact('pendingQuestions'));
    }

    /**
     * Display a listing of all questions (including answered ones).
     */
    public function allQuestions()
    {
        $user = Auth::user();

        // If super admin, show all questions
        if ($user->isSuperAdmin()) {
            $questions = Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular admin only sees questions from their assigned users
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            $questions = Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->whereIn('user_id', $assignedUserIds)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.questions.all', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created answer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'content' => 'required|string|min:5',
            'image' => 'nullable|image|max:5120', // 5MB limit
            'pdf' => 'nullable|mimes:pdf|max:10240', // 10MB limit
            'voice_data' => 'nullable|string', // Base64 encoded audio
        ]);

        $question = Question::findOrFail($request->question_id);
        $user = Auth::user();

        // Check if user is allowed to answer this question
        if (!$user->isSuperAdmin()) {
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            if (!in_array($question->user_id, $assignedUserIds)) {
                return redirect()->back()
                    ->with('error', 'You are not authorized to answer this question.');
            }
        }

        // Create the answer
        $answer = Answer::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'content' => $request->content,
            'is_pinned' => $request->has('is_pinned'),
            'is_accepted' => true,
        ]);

        // Handle image attachment
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('answer_attachments/images', 'public');

            AnswerAttachment::create([
                'answer_id' => $answer->id,
                'file_path' => $path,
                'original_name' => $image->getClientOriginalName(),
                'file_type' => 'image',
                'file_size' => $image->getSize(),
            ]);
        }

        // Handle PDF attachment
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $path = $pdf->store('answer_attachments/pdfs', 'public');

            AnswerAttachment::create([
                'answer_id' => $answer->id,
                'file_path' => $path,
                'original_name' => $pdf->getClientOriginalName(),
                'file_type' => 'pdf',
                'file_size' => $pdf->getSize(),
            ]);
        }

        // Handle voice recording (base64 data)
        if ($request->filled('voice_data')) {
            // Extract the actual base64 data from the data URL
            $base64Data = $request->voice_data;

            if (Str::startsWith($base64Data, 'data:audio')) {
                // Extract the audio type from the data URL (e.g., 'webm', 'ogg')
                $matches = [];
                preg_match('/data:audio\/(.*?);base64,/', $base64Data, $matches);
                $audioType = $matches[1] ?? 'webm';

                // Extract the actual base64 content
                $base64Data = explode(',', $base64Data)[1];

                // Decode the base64 data
                $decodedData = base64_decode($base64Data);

                // Generate a filename with the correct extension
                $filename = 'voice_recording_' . time() . '.' . $audioType;
                $path = 'answer_attachments/voice/' . $filename;

                // Store the file
                Storage::disk('public')->put($path, $decodedData);

                // Create attachment record
                AnswerAttachment::create([
                    'answer_id' => $answer->id,
                    'file_path' => $path,
                    'original_name' => $filename,
                    'file_type' => 'voice',
                    'file_size' => strlen($decodedData),
                    'mime_type' => 'audio/' . $audioType,
                ]);

                \Log::info('Voice recording saved with type: ' . $audioType);
            }
        }

        // Update question status to approved
        $question->status = 'approved';
        $question->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your answer has been submitted successfully.',
                'answer' => [
                    'id' => $answer->id,
                    'content' => $answer->content,
                    'created_at' => $answer->created_at->diffForHumans(),
                    'user' => Auth::user()->name,
                    'is_pinned' => $answer->is_pinned,
                    'is_accepted' => $answer->is_accepted,
                ]
            ]);
        }

        return redirect()->back()
            ->with('success', 'Answer submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Pin/unpin an answer.
     */
    public function togglePin(Answer $answer)
    {
        $user = Auth::user();
        $question = $answer->question;

        // Check if user is authorized
        if (!$user->isSuperAdmin()) {
            // Check if the question belongs to one of the admin's assigned users
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            if (!in_array($question->user_id, $assignedUserIds) && $answer->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to pin/unpin this answer.'
                ], 403);
            }
        }

        $answer->update([
            'is_pinned' => !$answer->is_pinned
        ]);

        return response()->json([
            'success' => true,
            'is_pinned' => $answer->fresh()->is_pinned
        ]);
    }

    /**
     * Reject a question without answering.
     */
    public function rejectQuestion(Question $question)
    {
        $user = Auth::user();

        // Check if user is allowed to reject this question
        if (!$user->isSuperAdmin()) {
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            if (!in_array($question->user_id, $assignedUserIds)) {
                return redirect()->back()
                    ->with('error', 'You are not authorized to reject this question.');
            }
        }

        $question->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Question marked as rejected.');
    }
}

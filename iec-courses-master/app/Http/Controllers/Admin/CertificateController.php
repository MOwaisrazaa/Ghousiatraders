<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CertificateRequest;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Super Admin']);
    }
    
    /**
     * Display a listing of certificate requests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendingRequests = CertificateRequest::where('status', 'pending')
            ->with(['user', 'course', 'lecture'])
            ->orderBy('created_at', 'asc')
            ->paginate(10);
            
        $issuedCertificates = Certificate::with(['user', 'course', 'lecture'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.certificates.index', compact('pendingRequests', 'issuedCertificates'));
    }
    
    /**
     * Display a specific certificate request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showRequest($id)
    {
        $request = CertificateRequest::with(['user', 'course', 'lecture'])
            ->findOrFail($id);
            
        // Get user's course progress
        $user = $request->user;
        $course = $request->course;
        $lecture = $request->lecture;
        
        $progressData = [];
        $quizAttempts = [];
        
        if ($lecture) {
            // Lecture certificate request - get the lecture's quiz
            $quiz = $lecture->quizzes()->first();
            if ($quiz) {
                $attempt = $quiz->getLatestAttempt($user->id);
                $quizAttempts[] = [
                    'quiz' => $quiz,
                    'attempt' => $attempt,
                    'passed' => $quiz->isPassed($user->id),
                ];
            }
        } else {
            // Course certificate request
            $progressData = [
                'percent' => $course->getProgressPercentageForUser($user->id),
                'completed_lectures' => $course->getCompletedLectureCountForUser($user->id),
                'total_lectures' => $course->total_lecture_count,
            ];
            
            // Get quiz attempts for all quizzes in the course
            $allQuizzes = $course->quizzes()->get();
            
            foreach ($allQuizzes as $quiz) {
                $attempt = $quiz->getLatestAttempt($user->id);
                $quizAttempts[] = [
                    'quiz' => $quiz,
                    'attempt' => $attempt,
                    'passed' => $quiz->isPassed($user->id),
                ];
            }
        }
        
        return view('admin.certificates.show_request', compact(
            'request', 
            'progressData',
            'quizAttempts'
        ));
    }
    
    /**
     * Approve a certificate request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $certRequest = CertificateRequest::findOrFail($id);
        
        // Only pending requests can be approved
        if ($certRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be approved.');
        }
        
        // Validate certificate upload
        $validator = Validator::make($request->all(), [
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Store the certificate file
        $path = $request->file('certificate_file')->store('certificates', 'public');
        
        // Update the certificate request
        $certRequest->status = 'approved';
        $certRequest->admin_notes = $request->admin_notes;
        $certRequest->reviewed_by = Auth::id();
        $certRequest->reviewed_at = now();
        $certRequest->save();
        
        // Create a certificate
        $certificate = Certificate::create([
            'user_id' => $certRequest->user_id,
            'course_id' => $certRequest->course_id,
            'lecture_id' => $certRequest->lecture_id,
            'certificate_request_id' => $certRequest->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'file_path' => $path,
            'issue_date' => now(),
            'expiry_date' => $request->has('expiry_date') ? $request->expiry_date : null,
        ]);
        
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate request approved and certificate issued successfully.');
    }
    
    /**
     * Reject a certificate request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $certRequest = CertificateRequest::findOrFail($id);
        
        // Only pending requests can be rejected
        if ($certRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be rejected.');
        }
        
        // Validate rejection reason
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string|min:10',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update the certificate request
        $certRequest->status = 'rejected';
        $certRequest->admin_notes = $request->admin_notes;
        $certRequest->reviewed_by = Auth::id();
        $certRequest->reviewed_at = now();
        $certRequest->save();
        
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate request rejected successfully.');
    }
    
    /**
     * View a certificate.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCertificate($id)
    {
        $certificate = Certificate::with(['user', 'course', 'lecture', 'certificateRequest'])
            ->findOrFail($id);
            
        return view('admin.certificates.view', compact('certificate'));
    }
    
    /**
     * Download a certificate.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        // Check if the certificate file exists
        if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
            return redirect()->back()
                ->with('error', 'Certificate file not found.');
        }
        
        // Get the file extension from the stored file
        $extension = pathinfo($certificate->file_path, PATHINFO_EXTENSION);
        
        // Build filename based on course/lecture
        if ($certificate->course) {
            $fileName = 'Certificate - ' . $certificate->course->name;
        } elseif ($certificate->lecture && $certificate->lecture->course) {
            $fileName = 'Certificate - ' . $certificate->lecture->course->name;
        } else {
            $fileName = 'Certificate';
        }
        
        if ($certificate->lecture) {
            $fileName .= ' - ' . $certificate->lecture->name;
        }
        $fileName .= ' - ' . $certificate->user->name . '.' . $extension;
        
        return response()->download(
            storage_path('app/public/' . $certificate->file_path), 
            $fileName
        );
    }
    
    /**
     * Show form to edit a certificate
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCertificate($id)
    {
        $certificate = Certificate::with(['user', 'course', 'lecture', 'certificateRequest'])
            ->findOrFail($id);
            
        return view('admin.certificates.edit', compact('certificate'));
    }
    
    /**
     * Update a certificate
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCertificate(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        
        // Validate request data
        $validator = Validator::make($request->all(), [
            'certificate_number' => 'required|string|max:255',
            'admin_notes' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update certificate details
        $certificate->certificate_number = $request->certificate_number;
        if ($request->has('expiry_date')) {
            $certificate->expiry_date = $request->expiry_date;
        }
        
        // If a new certificate file is uploaded, store it and update the path
        if ($request->hasFile('certificate_file')) {
            // Delete old file if it exists
            if ($certificate->file_path && Storage::exists($certificate->file_path)) {
                Storage::delete($certificate->file_path);
            }
            
            // Store new file
            $path = $request->file('certificate_file')->store('certificates', 'public');
            $certificate->file_path = $path;
        }
        
        $certificate->save();
        
        // Update certificate request notes if provided
        if ($request->has('admin_notes')) {
            $certRequest = CertificateRequest::find($certificate->certificate_request_id);
            if ($certRequest) {
                $certRequest->admin_notes = $request->admin_notes;
                $certRequest->save();
            }
        }
        
        return redirect()->route('admin.certificates.view', $certificate->id)
            ->with('success', 'Certificate updated successfully.');
    }
}

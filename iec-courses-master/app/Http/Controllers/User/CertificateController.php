<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\CertificateRequest;
use App\Models\Certificate;
use App\Models\QuizAttempt;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of user's certificates.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all certificates and pending requests
        $certificates = $user->certificates()->with(['course', 'lecture'])->get();
        $pendingRequests = $user->certificateRequests()
            ->where('status', 'pending')
            ->with(['course', 'lecture'])
            ->get();
        $rejectedRequests = $user->certificateRequests()
            ->where('status', 'rejected')
            ->with(['course', 'lecture'])
            ->get();
            
        return view('user.certificates.index', compact('certificates', 'pendingRequests', 'rejectedRequests'));
    }
    
    /**
     * Request a certificate for a lecture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lectureId
     * @return \Illuminate\Http\Response
     */
    public function requestLectureCertificate(Request $request, $lectureId)
    {
        $user = Auth::user();
        $lecture = Lecture::findOrFail($lectureId);
        $course = $lecture->course;
        $courseId = $course?->id;

        // If this is a course lecture, check if user purchased the full course
        if ($courseId) {
            $fullCourseAccess = UserCourse::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->whereNull('lecture_id')
                ->where('status', 'active')
                ->exists();

            if ($fullCourseAccess) {
                return redirect()->route('user.dashboard')
                    ->with('error', 'You purchased the full course. Please request a course certificate from your dashboard instead of individual lecture certificates.');
            }

            // Check if user has access to this specific lecture (lecture_id is set)
            $lecturePurchase = UserCourse::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->where('lecture_id', $lectureId)
                ->where('status', 'active')
                ->exists();

            if (!$lecturePurchase) {
                return redirect()->back()
                    ->with('error', 'You do not have access to this lecture.');
            }
        } else {
            // For standalone lectures, check if user has access
            $lecturePurchase = UserCourse::where('user_id', $user->id)
                ->where('lecture_id', $lectureId)
                ->where('status', 'active')
                ->exists();

            if (!$lecturePurchase) {
                return redirect()->back()
                    ->with('error', 'You do not have access to this lecture.');
            }
        }
        
        // Progress check: Lecture must be at least 90% completed or marked as completed
        $progress = $lecture->getProgressForUser($user->id);
        $progressPercent = $progress ? $progress->progress_percent : 0;
        $isCompleted = $progress ? $progress->completed : false;
        
        if ($progressPercent < 90 && !$isCompleted) {
            return redirect()->back()
                ->with('error', 'You need to complete at least 90% of the lecture before requesting a certificate.');
        }

        // Check if lecture has a quiz - if it does, user must pass it
        $quiz = $lecture->quizzes()->first();
        if ($quiz) {
            // Check if user has passed the quiz
            $passed = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'passed')
                ->exists();

            if (!$passed) {
                return redirect()->back()
                    ->with('error', 'You need to pass the lecture quiz before requesting a certificate.');
            }
        }
        // If no quiz exists, certificate can still be requested after completing the lecture

        // Check if they already have a pending request for this specific lecture
        if ($user->hasPendingCertificateRequest($courseId, $lectureId)) {
            return redirect()->back()
                ->with('error', 'You already have a pending certificate request for this lecture.');
        }

        // Check if they already have a certificate for this specific lecture
        $existingCert = $user->certificates()->where('lecture_id', $lectureId);
        if ($courseId) {
            $existingCert = $existingCert->where('course_id', $courseId);
        } else {
            $existingCert = $existingCert->whereNull('course_id');
        }
        
        $certificate = $existingCert->first();
        if ($certificate) {
             // If certificate exists, simply view it
             $filePath = storage_path('app/public/' . $certificate->file_path);
             if (file_exists($filePath)) {
                 return response()->file($filePath);
             }
             // Fallback if file missing
             return redirect()->back()->with('error', 'Certificate record found but file is missing.');
        }

        // Auto-generate certificate
        if (!extension_loaded('gd')) {
            return redirect()->back()
                ->with('error', 'System Error: PHP GD extension is not enabled. Please enable it in php.ini to generate certificates.');
        }

        DB::beginTransaction();
        try {
            // Create certificate request (automatically approved)
            // Use updateOrCreate to handle cases where a previous attempt failed but left a request record
            $certRequest = CertificateRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'lecture_id' => $lectureId,
                ],
                [
                    'status' => 'approved',
                    'reviewed_by' => null, // System generated
                    'reviewed_at' => now(),
                    'admin_notes' => 'Automatically generated upon lecture completion.'
                ]
            );
            
            // Generate valid certificate number
            $certificateNumber = Certificate::generateCertificateNumber();
            
            // Generate PDF
            $fileName = 'certificate_lec_' . $lectureId . '_' . $user->id . '_' . time() . '.pdf';
            
            // For lecture certificates, use the lecture name as the main title
            $certificateTitle = $lecture->name;
            
            $data = [
                'user' => $user,
                'courseName' => $certificateTitle,
                'certificateNumber' => $certificateNumber,
                'passingDate' => now()->format('F d, Y'),
                'hasQuizzes' => $quiz ? true : false,
                'usePublicPath' => true,
            ];
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists('certificates')) {
                Storage::disk('public')->makeDirectory('certificates');
            }
            
            // Generate and save PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', $data);
            $pdf->setPaper('a4', 'landscape');
            Storage::disk('public')->put('certificates/' . $fileName, $pdf->output());
            
            // Create Certificate Record
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'lecture_id' => $lectureId,
                'certificate_request_id' => $certRequest->id,
                'certificate_number' => $certificateNumber,
                'file_path' => 'certificates/' . $fileName,
                'issue_date' => now(),
            ]);

            DB::commit();

            // Stream the new certificate
            return response()->file(storage_path('app/public/certificates/' . $fileName));
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Certificate generation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error generating your certificate. Please contact support.');
        }
    }
    
    /**
     * Request a certificate for a course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function requestCertificate(Request $request, $courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // Check eligibility
        if (!$user->canRequestCertificate($courseId)) {
            $progressPercent = $course->getProgressPercentageForUser($user->id);
            
            // Determine the reason for ineligibility
            if ($progressPercent < 90) {
                return redirect()->back()
                    ->with('error', 'You need to complete at least 90% of the course before requesting a certificate. Your current progress is ' . number_format($progressPercent, 0) . '%.');
            }
            
            // Check if any required quizzes are not passed
            $requiredQuizIds = $course->quizzes()->where('required_for_completion', true)->pluck('id');
            foreach ($requiredQuizIds as $quizId) {
                $quiz = \App\Models\Quiz::find($quizId);
                if (!$quiz->isPassed($user->id)) {
                    return redirect()->back()
                    ->with('error', 'You need to pass all required quizzes before requesting a certificate. You have not passed: ' . $quiz->title);
                }
            }
            
            // Check if they already have a pending request
            if ($user->hasPendingCertificateRequest($courseId)) {
                return redirect()->back()
                    ->with('error', 'You already have a pending certificate request for this course.');
            }
            
            // If they have a certificate already
            $existingCert = $user->certificates()->where('course_id', $courseId)->whereNull('lecture_id')->first();
            if ($existingCert) {
                 // If certificate exists, simply view it
                 $filePath = storage_path('app/public/' . $existingCert->file_path);
                 if (file_exists($filePath)) {
                     return response()->file($filePath);
                 }
                 return redirect()->back()->with('error', 'Certificate record found but file is missing.');
            }
            
            return redirect()->back()
                ->with('error', 'You are not eligible for a certificate at this time.');
        }
        
        // Auto-generate certificate
        if (!extension_loaded('gd')) {
            return redirect()->back()
                ->with('error', 'System Error: PHP GD extension is not enabled. Please enable it in php.ini to generate certificates.');
        }

        DB::beginTransaction();
        try {
            // Create certificate request (automatically approved)
            // Use updateOrCreate to handle cases where a previous attempt failed but left a request record
            $certRequest = CertificateRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'lecture_id' => null,
                ],
                [
                    'status' => 'approved',
                    'reviewed_by' => null, // System generated
                    'reviewed_at' => now(),
                    'admin_notes' => 'Automatically generated upon course completion.'
                ]
            );
            
            // Generate valid certificate number
            $certificateNumber = Certificate::generateCertificateNumber();
            
            // Generate PDF
            $fileName = 'certificate_' . $courseId . '_' . $user->id . '_' . time() . '.pdf';
            $data = [
                'user' => $user,
                'courseName' => $course->name,
                'certificateNumber' => $certificateNumber,
                'passingDate' => now()->format('F d, Y'),
                'hasQuizzes' => $course->quizzes()->exists(),
                'usePublicPath' => true,
            ];
            
            // Ensure directory exists
            if (!Storage::disk('public')->exists('certificates')) {
                Storage::disk('public')->makeDirectory('certificates');
            }
            
            // Generate and save PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.certificate', $data);
            $pdf->setPaper('a4', 'landscape');
            Storage::disk('public')->put('certificates/' . $fileName, $pdf->output());
            
            // Create Certificate Record
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'lecture_id' => null,
                'certificate_request_id' => $certRequest->id,
                'certificate_number' => $certificateNumber,
                'file_path' => 'certificates/' . $fileName,
                'issue_date' => now(),
            ]);
            
            DB::commit();

            // Stream the new certificate
            return response()->file(storage_path('app/public/certificates/' . $fileName));
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Certificate generation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'There was an error generating your certificate. Please contact support.');
        }
    }
    
    /**
     * Download a certificate.
     *
     * @param  int  $certificateId
     * @return \Illuminate\Http\Response
     */
    public function download($certificateId)
    {
        $user = Auth::user();
        $certificate = Certificate::where('id', $certificateId)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Check if the certificate file exists
        if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
            return redirect()->back()
                ->with('error', 'Certificate file not found. Please contact support.');
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
        $fileName .= '.' . $extension;
        
        return response()->download(
            storage_path('app/public/' . $certificate->file_path), 
            $fileName
        );
    }
    
    /**
     * View a certificate.
     *
     * @param  int  $certificateId
     * @return \Illuminate\Http\Response
     */
    public function view($certificateId)
    {
        $user = Auth::user();
        $certificate = Certificate::where('id', $certificateId)
            ->where('user_id', $user->id)
            ->with(['course', 'lecture', 'user'])
            ->firstOrFail();
            
        return view('user.certificates.view', compact('certificate'));
    }

    /**
     * Stream a certificate file (for inline viewing).
     *
     * @param  int  $certificateId
     * @return \Illuminate\Http\Response
     */
    public function stream($certificateId)
    {
        $user = Auth::user();
        $certificate = Certificate::where('id', $certificateId)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Check if the certificate file exists
        if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
            abort(404, 'Certificate file not found.');
        }
        
        return response()->file(storage_path('app/public/' . $certificate->file_path));
    }
}

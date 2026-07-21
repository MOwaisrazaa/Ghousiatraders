@if($course->status === 'approved')
    <a href="{{ route('course.detail', $course->id) }}" class="btn btn-sm btn-primary mb-0">
        View Course
    </a>
    <a href="{{ route('user.course.purchased', $course->id) }}" class="btn btn-sm btn-info mb-0">
        View Course Content
    </a>
@else
    @if($course->status === 'pending')
        <span class="badge bg-warning text-dark">Pending Approval</span>
    @elseif($course->status === 'rejected')
        <span class="badge bg-danger">Rejected</span>
    @endif
@endif

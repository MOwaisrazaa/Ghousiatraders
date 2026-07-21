@extends('admin.layout')

@section('title', 'Review Management')

@section('header', 'Reviews')

@section('content')
    <div class="pf-table-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">All Product Reviews</span>
            <span class="pf-badge-page">{{ $reviews->total() }} total</span>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success mx-4 my-2 polani-admin-alert" role="alert" style="background: rgba(79,200,100,0.1); color: #5fcf6e; border: 1px solid rgba(79,200,100,0.2);">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="pf-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Product</th>
                        <th>Reviewer Name</th>
                        <th style="width: 120px;">Rating</th>
                        <th>Comment</th>
                        <th style="width: 120px;">Date</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>
                                @if($review->rateable)
                                    <strong>{{ $review->rateable->name }}</strong>
                                    <br>
                                    <small style="color: rgba(212,166,88,0.65);">{{ $review->rateable->slug }}</small>
                                @else
                                    <span style="color: rgba(248,231,208,0.35); font-style: italic;">Unknown Product</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $review->reviewer_name ?? ($review->user ? $review->user->name : 'Anonymous') }}</strong>
                                @if($review->user)
                                    <br>
                                    <small style="color: rgba(248, 231, 208, 0.55);">{{ $review->user->email }}</small>
                                    <br>
                                    <span style="font-size: 0.72rem; background: rgba(59, 130, 246, 0.15); color: #7ab8ff; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 3px;">
                                        Registered User
                                    </span>
                                @else
                                    <br>
                                    <span style="font-size: 0.72rem; background: rgba(212, 166, 88, 0.15); color: #d4a658; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 3px;">
                                        Guest Buyer
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="color: #d4a658; font-size: 1rem;">
                                    {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                                </span>
                                <br>
                                <small style="color: rgba(248,231,208,0.5);">({{ $review->rating }}/5)</small>
                            </td>
                            <td>
                                <div style="max-width: 300px; max-height: 80px; overflow-y: auto; font-size: 0.88rem; line-height: 1.5; color: rgba(248,231,208,0.85); word-wrap: break-word; white-space: normal;">
                                    {{ $review->comment ?: 'No text feedback provided.' }}
                                </div>
                            </td>
                            <td>
                                <small>{{ $review->created_at->format('M d, Y') }}</small>
                                <br>
                                <small style="color: rgba(248,231,208,0.4);">{{ $review->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($review->show_publicly)
                                    <span class="pf-btn-success" style="background: rgba(79,200,100,0.12); color: #5fcf6e; border: 1px solid rgba(79,200,100,0.3); padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Approved
                                    </span>
                                @else
                                    <span class="pf-btn-delete" style="background: rgba(220,53,69,0.12); color: #f07080; border: 1px solid rgba(220,53,69,0.3); padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                        Hidden
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="pf-btn-edit" style="min-width: 80px; text-align: center; justify-content: center;" title="{{ $review->show_publicly ? 'Hide Review' : 'Approve Review' }}">
                                            @if($review->show_publicly)
                                                <i class="fas fa-eye-slash"></i> Hide
                                            @else
                                                <i class="fas fa-check"></i> Approve
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this review? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pf-btn-delete" title="Delete Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="pf-empty" style="text-align: center; padding: 40px; color: rgba(248,231,208,0.45); font-style: italic;">
                                <i class="fas fa-comment-slash" style="font-size: 1.5rem; display: block; margin-bottom: 10px; color: rgba(212,166,88,0.35);"></i>
                                No product reviews found in database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pf-pagination mt-4" style="padding:16px 24px;">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection

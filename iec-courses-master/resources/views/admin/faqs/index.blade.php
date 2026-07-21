@extends('admin.layout')

@section('title', 'FAQ Management')
@section('header', 'FAQ Management')

@section('content')
    <div class="polani-stats-grid mb-4">
        <div class="polani-stat-card polani-stat-card--gold">
            <div class="polani-stat-card__label">Total Questions</div>
            <div class="polani-stat-card__value">{{ $stats['total'] }}</div>
        </div>

        <div class="polani-stat-card polani-stat-card--amber">
            <div class="polani-stat-card__label">Unanswered</div>
            <div class="polani-stat-card__value text-warning">{{ $stats['pending'] }}</div>
        </div>

        <div class="polani-stat-card polani-stat-card--emerald">
            <div class="polani-stat-card__label">Answered</div>
            <div class="polani-stat-card__value text-success">{{ $stats['answered'] }}</div>
        </div>

        <div class="polani-stat-card polani-stat-card--blue">
            <div class="polani-stat-card__label">Published to FAQ</div>
            <div class="polani-stat-card__value text-info">{{ $stats['published'] }}</div>
        </div>
    </div>

    <div class="pf-table-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">All Submitted Questions</span>
            <span class="pf-badge-page">{{ $faqs->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="pf-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>User Info</th>
                        <th>Question</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Asked Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>
                                <div style="display:flex;flex-direction:column;">
                                    <strong style="color:#f8e7d0;">{{ $faq->name ?: 'Guest' }}</strong>
                                    <small style="color:rgba(248,231,208,0.5);">{{ $faq->email ?: 'No Email' }}</small>
                                </div>
                            </td>
                            <td>
                                <span title="{{ $faq->question }}">{{ Str::limit($faq->question, 60) }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: {{ $faq->answer ? 'rgba(40, 167, 69, 0.15)' : 'rgba(255, 193, 7, 0.15)' }}; color: {{ $faq->answer ? '#2ecc71' : '#ffe69c' }}; border: 1px solid {{ $faq->answer ? '#28a745' : '#ffc107' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">
                                    {{ $faq->answer ? 'Answered' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: {{ $faq->is_published ? 'rgba(23, 162, 184, 0.15)' : 'rgba(108, 117, 125, 0.15)' }}; color: {{ $faq->is_published ? '#17a2b8' : '#dee2e6' }}; border: 1px solid {{ $faq->is_published ? '#17a2b8' : '#6c757d' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">
                                    {{ $faq->is_published ? 'Published' : 'Hidden' }}
                                </span>
                            </td>
                            <td>{{ $faq->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="pf-btn-edit" style="background:rgba(212,166,88,0.12);color:#d4a658;border:1px solid rgba(212,166,88,0.3); padding: 4px 10px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; text-decoration: none;" title="{{ $faq->answer ? 'Edit Answer' : 'Answer' }}">
                                        <i class="fas fa-reply"></i> {{ $faq->answer ? 'Edit' : 'Answer' }}
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pf-btn-delete" title="Delete" style="padding: 4px 10px; font-size: 0.8rem; border-radius: 6px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pf-empty">No questions submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pf-pagination" style="padding:16px 24px;">
            {{ $faqs->links() }}
        </div>
    </div>
@endsection

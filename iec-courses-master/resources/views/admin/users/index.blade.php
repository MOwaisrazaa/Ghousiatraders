@extends('admin.layout')

@section('title', 'Users Management')

@section('header', 'Users Management')

@section('actions')
    <a href="{{ route('admin.user.create') }}" class="pf-btn-gold">
        <i class="fas fa-user-plus"></i> Add New User
    </a>
@endsection

@section('content')
    <div class="pf-table-wrap">
        <div class="table-responsive">
            <table class="pf-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;background:rgba(212,166,88,0.14);color:#d4a658;border:1px solid rgba(212,166,88,0.32);margin:2px 2px;">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('users.edit', $user->id) }}" class="pf-btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($user->id != auth()->id())
                                        <button class="pf-btn-delete delete-btn" data-id="{{ $user->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pf-empty">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pf-pagination mt-4" style="padding:16px 24px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/${userId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endsection

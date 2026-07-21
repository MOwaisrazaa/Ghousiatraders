@extends('admin.layout')

@section('title', 'Create Instructor Profile')

@section('header')
    Create Instructor Profile
@endsection

@section('actions')
    <a href="{{ route('admin.instructor-profiles') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.instructor-profiles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Senior Instructor, Professor of Mathematics">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biography</label>
                            <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="Brief description of the instructor's background...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Profile Image -->
                        <div class="mb-3">
                            <label for="image_path" class="form-label">Profile Image</label>
                            <input type="file" id="image_path" name="image_path" class="form-control @error('image_path') is-invalid @enderror" accept="image/*">
                            @error('image_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Skills & Expertise -->
                        <div class="mb-3">
                            <label for="expertise" class="form-label">Areas of Expertise</label>
                            <input type="text" id="expertise" name="expertise" class="form-control @error('expertise') is-invalid @enderror" value="{{ old('expertise') }}" placeholder="e.g., Machine Learning, Data Science, Web Development">
                            <small class="form-text text-muted">Separate with commas</small>
                            @error('expertise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills</label>
                            <input type="text" id="skills" name="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills') }}" placeholder="e.g., Python, JavaScript, TensorFlow">
                            <small class="form-text text-muted">Separate with commas</small>
                            @error('skills')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Social Links -->
                        <div class="mb-3">
                            <label for="social_linkedin" class="form-label">LinkedIn</label>
                            <input type="url" id="social_linkedin" name="social_linkedin" class="form-control @error('social_linkedin') is-invalid @enderror" value="{{ old('social_linkedin') }}" placeholder="https://linkedin.com/in/profile">
                            @error('social_linkedin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="social_twitter" class="form-label">Twitter</label>
                            <input type="url" id="social_twitter" name="social_twitter" class="form-control @error('social_twitter') is-invalid @enderror" value="{{ old('social_twitter') }}" placeholder="https://twitter.com/handle">
                            @error('social_twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="social_website" class="form-label">Website</label>
                            <input type="url" id="social_website" name="social_website" class="form-control @error('social_website') is-invalid @enderror" value="{{ old('social_website') }}" placeholder="https://example.com">
                            @error('social_website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('admin.instructor-profiles') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Instructor Profile</button>
                </div>
            </form>
        </div>
    </div>
@endsection 
<div>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">Our Instructors</h1>
                <p class="lead text-muted">Meet our team of professional instructors who are experts in their fields.</p>
            </div>
            <div class="col-md-4">
                <div class="input-group mt-3">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Search instructors" 
                        wire:model.live.debounce.300ms="search"
                    >
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>

        @if($instructorProfiles->isEmpty())
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <p class="mb-0">No instructor profiles found. Please try a different search term.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($instructorProfiles as $profile)
                    <div class="col">
                        <div class="card h-100 shadow-sm hover-shadow instructor-card">
                            <!-- Decorative top bar with initial -->
                            <div class="instructor-card-header position-relative">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="badge bg-white text-primary fw-bold fs-6 px-3 py-2 shadow-sm">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-center pt-5 mt-2">
                                @if($profile->image_path)
                                    <img src="{{ Storage::url($profile->image_path) }}" 
                                        class="rounded-circle mb-3 instructor-img-cover" 
                                        width="120" 
                                        height="120" 
                                        alt="{{ $profile->name }}">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 instructor-avatar">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold mb-1">{{ $profile->name }}</h5>
                                <p class="text-muted small mb-3">{{ $profile->title }}</p>
                                
                                <div class="mb-3">
                                    @php
                                        $expertiseArray = is_string($profile->expertise) ? 
                                            explode(',', $profile->expertise) : 
                                            (is_array(json_decode($profile->expertise, true)) ? 
                                                json_decode($profile->expertise, true) : []);
                                    @endphp
                                    
                                    @foreach(array_slice($expertiseArray, 0, 3) as $expertise)
                                        <span class="badge bg-primary me-1">{{ trim($expertise) }}</span>
                                    @endforeach
                                    @if(count($expertiseArray) > 3)
                                        <span class="badge bg-light text-dark">+{{ count($expertiseArray) - 3 }} more</span>
                                    @endif
                                </div>
                                
                                @if(!empty($profile->bio))
                                    <p class="card-text small mb-3">
                                        {{ Str::limit($profile->bio, 120) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    @if(!empty($profile->social_linkedin))
                                        <a href="{{ $profile->social_linkedin }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!empty($profile->social_twitter))
                                        <a href="{{ $profile->social_twitter }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif
                                    
                                    @if(!empty($profile->social_website))
                                        <a href="{{ $profile->social_website }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-globe"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center">
                                <a href="#" class="btn btn-sm btn-primary" wire:click.prevent="viewCourses('{{ $profile->name }}')">
                                    View Courses <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $instructorProfiles->links() }}
            </div>
        @endif
    </div>
</div> 
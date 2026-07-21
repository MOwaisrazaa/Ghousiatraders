<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container">
            <h2>Courses and Lectures</h2>
            <div class="text-end mb-3">
                <label for="togglePrice" class="form-check-label">Show Weekly Price</label>
                <input type="checkbox" id="togglePrice" class="form-check-input" checked>
            </div>
            <div class="row">
                @foreach ($courses as $course)
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card border shadow-xs">
                        <div class="card-body text-start p-3 w-100">
                            <div class="w-100 text-center mb-3">
                                <img src="{{ Storage::url($course->image_path) }}" alt="{{ $course->courseName->name }}"
                                    class="img-fluid border-radius-sm">
                            </div>
                            <h4 class="mb-2 font-weight-bold text-dark">{{ $course->courseName->name }}</h4>
                            <h5 class="text-sm text-secondary mb-1">Lecture: {{ $course->lecture_name }}</h5>
                            <div class="text-sm text-secondary mb-3">{!! $course->description !!}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="text-primary mb-0 font-weight-bolder">
                                    $<span class="price">{{ $course->weekly_price }}</span>
                                </h5>
                                <a href="{{ $course->youtube_url }}" target="_blank" class="btn btn-primary btn-sm">
                                    Watch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <x-app.footer />
    </main>

    <script>
        const toggle = document.getElementById('togglePrice');
        toggle.addEventListener('change', () => {
            const prices = document.querySelectorAll('.price');
            prices.forEach(price => {
                const weekly = price.getAttribute('data-weekly');
                const monthly = price.getAttribute('data-monthly');
                price.textContent = toggle.checked ? weekly : monthly;
            });
        });
    </script>
</x-app-layout>

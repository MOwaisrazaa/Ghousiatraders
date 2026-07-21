<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Quiz Attempts</h1>
                <p class="mb-0">All attempts for: {{ $quiz->title }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> View Quiz Details
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Quizzes
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Attempts</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attempts->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Passing Rate</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $totalAttempts = $attempts->total();
                                        $passedAttempts = $attempts->where('passed', true)->count();
                                        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0;
                                    @endphp
                                    {{ $passRate }}%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Unique Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $uniqueStudentCount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">All Attempts</h6>
                <div>
                    <form class="d-flex" action="{{ route('admin.quizzes.attempts', $quiz) }}" method="GET">
                        <select class="form-select form-select-sm me-2" name="result" onchange="this.form.submit()">
                            <option value="">All Results</option>
                            <option value="passed" {{ request('result') === 'passed' ? 'selected' : '' }}>Passed Only</option>
                            <option value="failed" {{ request('result') === 'failed' ? 'selected' : '' }}>Failed Only</option>
                        </select>
                        <input type="text" class="form-control form-control-sm me-2" placeholder="Search by name..." name="search" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($attempts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No attempts found for this quiz.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="attemptsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Result</th>
                                    <th>Time Spent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $attempt->user) }}">
                                                {{ $attempt->user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $attempt->created_at->format('M d, Y h:i A') }}</td>
                                        <td>{{ $attempt->score }}%</td>
                                        <td>
                                            @if($attempt->passed)
                                                <span class="badge bg-success">Passed</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attempt->time_spent)
                                                {{ floor($attempt->time_spent / 60) }}m {{ $attempt->time_spent % 60 }}s
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.quiz-attempts.show', $attempt) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye" title="View Details"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $attempts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Performance Analytics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Score Distribution</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="scoreDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Results Over Time</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="resultsOverTimeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Most Challenging Questions</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Question</th>
                                                <th>Type</th>
                                                <th>Points</th>
                                                <th>Success Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($questionStats as $stat)
                                                <tr>
                                                    <td>{{ Str::limit($stat['question_text'], 100) }}</td>
                                                    <td>{{ ucfirst($stat['question_type']) }}</td>
                                                    <td>{{ $stat['points'] }}</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-{{ $stat['success_rate'] < 40 ? 'danger' : ($stat['success_rate'] < 70 ? 'warning' : 'success') }} dynamic-progress-bar"
                                                                role="progressbar"
                                                                data-width="{{ $stat['success_rate'] }}"
                                                                aria-valuenow="{{ $stat['success_rate'] }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                {{ $stat['success_rate'] }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Score Distribution Chart
            const scoreDistributionCtx = document.getElementById('scoreDistributionChart');
            new Chart(scoreDistributionCtx, {
                type: 'bar',
                data: {
                    labels: @json($scoreDistribution['labels']),
                    datasets: [{
                        label: 'Number of Students',
                        data: @json($scoreDistribution['data']),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Results Over Time Chart
            const resultsOverTimeCtx = document.getElementById('resultsOverTimeChart');
            new Chart(resultsOverTimeCtx, {
                type: 'line',
                data: {
                    labels: @json($resultsOverTime['labels']),
                    datasets: [{
                        label: 'Pass Rate (%)',
                        data: @json($resultsOverTime['data']),
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 
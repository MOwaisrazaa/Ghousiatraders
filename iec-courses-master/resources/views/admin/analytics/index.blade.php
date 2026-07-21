@extends('admin.layout')

@section('title', 'Traffic Analytics')

@section('header', 'Traffic Analytics')

@push('styles')
<style>
    .card-stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <!-- Total Visits -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 bg-primary text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Visits (30 Days)</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($totalVisits) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="card-stat-icon">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unique Visitors -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 bg-success text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Unique Visitors</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($uniqueVisitors) }}</div>
                    </div>
                    <div class="col-auto">
                         <div class="card-stat-icon">
                            <i class="fas fa-user-friends fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Country -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 bg-info text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Top Country</div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $visitsByCountry->first() ? ($visitsByCountry->first()->country ?? 'Unknown') : 'N/A' }}
                        </div>
                    </div>
                    <div class="col-auto">
                         <div class="card-stat-icon">
                            <i class="fas fa-globe fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Traffic -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow h-100 py-2 bg-warning text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Most Used Device</div>
                        <div class="h5 mb-0 font-weight-bold">
                            @php
                                $topDevice = $deviceStats->sortByDesc('total')->first();
                            @endphp
                            {{ $topDevice ? ucfirst($topDevice->device_type) : 'N/A' }}
                        </div>
                    </div>
                    <div class="col-auto">
                         <div class="card-stat-icon">
                            <i class="fas fa-mobile-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Traffic Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Traffic Overview (Last 30 Days)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 320px;">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Device Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                    <canvas id="deviceChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    @foreach($deviceStats as $device)
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> {{ ucfirst($device->device_type) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Countries -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Countries</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Visits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitsByCountry as $country)
                                <tr>
                                    <td>{{ $country->country ?? 'Unknown' }}</td>
                                    <td>{{ number_format($country->total) }}</td>
                                </tr>
                            @endforeach
                            @if($visitsByCountry->isEmpty())
                                <tr><td colspan="2" class="text-center">No data available</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
<div class="row">
    <!-- Top Landing Pages -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Landing Pages (External Traffic)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Entries</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topLandingPages as $page)
                                <tr>
                                    <td class="text-truncate" style="max-width: 300px;" title="{{ $page->url }}">{{ Str::limit($page->url, 50) }}</td>
                                    <td>{{ number_format($page->total) }}</td>
                                </tr>
                            @endforeach
                            @if($topLandingPages->isEmpty())
                                <tr><td colspan="2" class="text-center">No data available</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="small text-muted mt-2">
                    * Pages where visitors enter your site from outside. This is a strong indicator of what keywords they searched for.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Internal Searches -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Internal Search Terms</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Searches</th>
                                <th>Avg Results</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topSearches as $search)
                                <tr>
                                    <td>{{ $search->keyword }}</td>
                                    <td>{{ number_format($search->total) }}</td>
                                    <td>{{ number_format($search->avg_results, 1) }}</td>
                                </tr>
                            @endforeach
                            @if($topSearches->isEmpty())
                                <tr><td colspan="3" class="text-center">No searches yet</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Pages (General) -->
    <div class="col-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Top Visited Pages</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Page URL</th>
                                <th>Total Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPages as $page)
                                <tr>
                                    <td class="text-truncate" style="max-width: 600px;" title="{{ $page->url }}">{{ Str::limit($page->url, 80) }}</td>
                                    <td>{{ number_format($page->total) }}</td>
                                </tr>
                            @endforeach
                            @if($topPages->isEmpty())
                                <tr><td colspan="2" class="text-center">No data available</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Referrers -->
    <div class="col-12 mb-4">
         <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Referrers</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Referrer</th>
                                <th>Visits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topReferrers as $ref)
                                <tr>
                                    <td class="text-truncate" style="max-width: 500px;" title="{{ $ref->referer }}">{{ Str::limit($ref->referer, 80) }}</td>
                                    <td>{{ number_format($ref->total) }}</td>
                                </tr>
                            @endforeach
                            @if($topReferrers->isEmpty())
                                <tr><td colspan="2" class="text-center">No data available. (Most traffic might be Direct)</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Traffic Chart
    var ctx = document.getElementById("trafficChart");
    var trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyVisits->pluck('date')) !!},
            datasets: [{
                label: "Visits",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: {!! json_encode($dailyVisits->pluck('total')) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                x: { grid: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } },
                y: { ticks: { maxTicksLimit: 5, padding: 10, callback: function(value, index, values) { return value; } }, grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleColor: '#6e707e',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            }
        }
    });

    // Device Chart
    var ctxDevice = document.getElementById("deviceChart");
    var deviceChart = new Chart(ctxDevice, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceStats->pluck('device_type')->map(fn($t) => ucfirst($t))) !!},
            datasets: [{
                data: {!! json_encode($deviceStats->pluck('total')) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                }
            },
            cutout: '80%',
        },
    });
</script>
@endpush
@endsection

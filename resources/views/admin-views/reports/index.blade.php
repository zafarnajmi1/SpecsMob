@extends('admin-views.layouts.admin')

@section('title', 'System Reports')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>System Reports</h3>
                    <p class="text-subtitle text-muted">Summary reports for platform audit and growth tracking.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                {{-- Device Catalog Status --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100">
                        <div class="card-header bg-light-primary border-bottom py-3">
                            <h5 class="card-title text-primary mb-0"><i class="bi bi-box-seam me-2"></i>Device Catalog
                                Status</h5>
                        </div>
                        <div class="card-body py-4">
                            @foreach($summary['device_counts'] as $status)
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <span
                                        class="text-capitalize fw-bold">{{ str_replace('_', ' ', $status->release_status) }}</span>
                                    <span class="badge bg-primary rounded-pill px-3">{{ number_format($status->count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- User Growth Summary --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100">
                        <div class="card-header bg-light-success border-bottom py-3">
                            <h5 class="card-title text-success mb-0"><i class="bi bi-graph-up-arrow me-2"></i>User Growth
                            </h5>
                        </div>
                        <div class="card-body py-4">
                            <div class="text-center mb-4">
                                <h2 class="display-6 fw-bold mb-0 text-success">
                                    {{ number_format($summary['user_stats']['total']) }}</h2>
                                <small class="text-muted">Total Registered Users</small>
                            </div>
                            <div class="row text-center border-top pt-3">
                                <div class="col-6 border-end">
                                    <h4 class="mb-0">{{ number_format($summary['user_stats']['new_today']) }}</h4>
                                    <small class="text-muted">New Today</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="mb-0">{{ number_format($summary['user_stats']['new_this_week']) }}</h4>
                                    <small class="text-muted">This Week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content Production --}}
                <div class="col-md-4 col-12">
                    <div class="card h-100">
                        <div class="card-header bg-light-info border-bottom py-3">
                            <h5 class="card-title text-info mb-0"><i class="bi bi-journal-check me-2"></i>Content Production
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush mt-2">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="bi bi-newspaper me-2"></i> News Articles</span>
                                    <span class="fw-bold">{{ number_format($summary['content_stats']['news']) }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="bi bi-file-text me-2"></i> Blog Posts</span>
                                    <span class="fw-bold">{{ number_format($summary['content_stats']['articles']) }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="bi bi-search me-2"></i> Device Reviews</span>
                                    <span class="fw-bold">{{ number_format($summary['content_stats']['reviews']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Brand Concentration --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Brand Concentration (Top 10)</h4>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i
                                    class="bi bi-printer me-2"></i>Print Report</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Brand Model</th>
                                            <th>Device Count</th>
                                            <th>Catalog Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalDevices = array_sum($summary['device_counts']->pluck('count')->toArray()); @endphp
                                        @foreach($brand_distribution as $brand)
                                            <tr>
                                                <td class="fw-bold">{{ $brand->name }}</td>
                                                <td>{{ number_format($brand->devices_count) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress w-100 me-3" style="height: 10px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $totalDevices > 0 ? ($brand->devices_count / $totalDevices) * 100 : 0 }}%">
                                                            </div>
                                                        </div>
                                                        <span>{{ $totalDevices > 0 ? number_format(($brand->devices_count / $totalDevices) * 100, 1) : 0 }}%</span>
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
        </section>
    </div>
@endsection
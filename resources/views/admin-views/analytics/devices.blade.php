@extends('admin-views.layouts.admin')

@section('title', 'Device Analytics')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Device Analytics</h3>
                    <p class="text-subtitle text-muted">Tracking interest, fans, and comparisons for all devices.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Device Analytics</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                {{-- Top Daily Interest --}}
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header bg-primary py-3">
                            <h4 class="card-title text-white mb-0"><i class="bi bi-lightning-fill me-2"></i>Top 20 by Daily
                                Interest</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Device</th>
                                            <th class="text-end">Daily Hits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_daily as $device)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $device->thumbnail_url }}" alt="" class="rounded me-2"
                                                            style="width: 30px; height: 30px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold">{{ $device->name }}</div>
                                                            <small class="text-muted">{{ $device->brand->name ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold text-primary">
                                                    {{ number_format($device->daily_hits) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Fans --}}
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header bg-danger py-3">
                            <h4 class="card-title text-white mb-0"><i class="bi bi-heart-fill me-2"></i>Top 20 by Fans</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Device</th>
                                            <th class="text-end">Fans</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_fans as $device)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $device->thumbnail_url }}" alt="" class="rounded me-2"
                                                            style="width: 30px; height: 30px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold">{{ $device->name }}</div>
                                                            <small class="text-muted">{{ $device->brand->name ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end fw-bold text-danger">
                                                    {{ number_format($device->total_fans) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Top All Time Hits --}}
                <div class="col-md-7 col-12">
                    <div class="card">
                        <div class="card-header border-bottom py-3">
                            <h4 class="card-title mb-0"><i class="bi bi-award me-2"></i>Most Popular All-Time</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="table-light">
                                            <th width="50">#</th>
                                            <th>Device</th>
                                            <th>Brand</th>
                                            <th class="text-end">Total Hits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_all_time as $index => $device)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><span class="fw-bold">{{ $device->name }}</span></td>
                                                <td>{{ $device->brand->name ?? 'N/A' }}</td>
                                                <td class="text-end">{{ number_format($device->total_hits) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Popular Comparisons --}}
                <div class="col-md-5 col-12">
                    <div class="card border border-info">
                        <div class="card-header bg-info-light py-3">
                            <h4 class="card-title text-info mb-0"><i class="bi bi-arrow-left-right me-2"></i>Popular
                                Comparisons</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($popular_comparisons as $comp)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span
                                                class="badge bg-light-primary text-primary">{{ $comp->device1->name ?? 'Deleted' }}</span>
                                            <small class="text-muted">vs</small>
                                            <span
                                                class="badge bg-light-secondary text-secondary">{{ $comp->device2->name ?? 'Deleted' }}</span>
                                        </div>
                                        <span class="badge bg-pill bg-info">{{ number_format($comp->hits) }} views</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
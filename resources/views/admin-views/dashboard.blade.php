@extends('admin-views.layouts.admin')
@section('title', 'Admin Dashboard')
@push('styles')
    <style>
        .stats-icon-modern {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .purple-gradient { background: linear-gradient(135deg, #9694ff 0%, #435ebe 100%); }
        .blue-gradient { background: linear-gradient(135deg, #57caeb 0%, #2575fc 100%); }
        .green-gradient { background: linear-gradient(135deg, #5ddab4 0%, #198754 100%); }
        .red-gradient { background: linear-gradient(135deg, #ff7976 0%, #dc3545 100%); }
        
        .card:hover {
            transform: translateY(-5px);
        }
        .card {
            background: #ffffff;
            transition: all 0.3s ease-in-out;
        }
    </style>
@endpush
@section('content')
    <div class="page-heading">
        <h3>Profile Statistics</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <!-- Total Devices -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-modern purple-gradient me-3 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-phone text-white fs-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-muted mb-0 font-semibold text-truncate" style="font-size: 0.85rem;">Devices</p>
                                        <h4 class="font-extrabold mb-0 mt-1">{{ number_format($data['stats']['totalDevices']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Brands -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-modern blue-gradient me-3 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-tag text-white fs-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-muted mb-0 font-semibold text-truncate" style="font-size: 0.85rem;">Brands</p>
                                        <h4 class="font-extrabold mb-0 mt-1">{{ number_format($data['stats']['totalBrands']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Articles -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-modern green-gradient me-3 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-newspaper text-white fs-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-muted mb-0 font-semibold text-truncate" style="font-size: 0.85rem;">Articles</p>
                                        <h4 class="font-extrabold mb-0 mt-1">{{ number_format($data['stats']['totalArticles']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Total Comments -->
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px; transition: transform 0.3s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-modern red-gradient me-3 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-chat-dots text-white fs-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-muted mb-0 font-semibold text-truncate" style="font-size: 0.85rem;">Comments</p>
                                        <h4 class="font-extrabold mb-0 mt-1">{{ number_format($data['stats']['totalComments']) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Registrations & Engagements</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-profile-visit"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Top Countries</h4>
                            </div>
                            <div class="card-body">
                                @php 
                                    $colors = ['text-primary', 'text-success', 'text-danger', 'text-warning', 'text-info'];
                                @endphp
                                @foreach($data['countryDistribution'] as $index => $dist)
                                <div class="row align-items-center mb-2">
                                    <div class="col-8">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-circle-fill {{ $colors[$index % 5] }} me-3" style="font-size: 0.6rem;"></i>
                                            <h5 class="mb-0 text-truncate" style="font-size: 0.9rem;">{{ $dist->country }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <h5 class="mb-0 text-end" style="font-size: 0.9rem;">{{ $dist->total }}</h5>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <div id="chart-country-{{ $index }}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Latest Comments</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['latestComments'] as $comment)
                                                <tr>
                                                    <td class="col-4">
                                                        <div class="d-flex align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 40px; height: 40px; border-radius: 50%;">
                                                                @php
                                                                    $commentUserImage = $comment->user->image ?? null;
                                                                    $isCommentImageExists = $commentUserImage && (str_starts_with($commentUserImage, 'http') || Storage::disk('public')->exists($commentUserImage));
                                                                @endphp
                                                                @if($isCommentImageExists)
                                                                    <img src="{{ $comment->user->image_url }}" alt="AV" style="width: 100%; height: 100%; object-fit: cover;">
                                                                @else
                                                                    <span class="font-bold">{{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="ms-3 mb-0">
                                                                <p class="font-bold mb-0 text-sm">
                                                                    {{ $comment->user->name ?? 'Anonymous' }}</p>
                                                                <small class="text-muted"
                                                                    style="font-size: 10px">{{ $comment->created_at->diffForHumans() }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class="mb-0 text-sm">{{ Str::limit($comment->body, 80) }}</p>
                                                        <small class="text-muted">on
                                                            {{ class_basename($comment->commentable_type) }}:
                                                            {{ $comment->commentable->name ?? $comment->commentable->title ?? '...' }}
                                                        </small>
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
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl bg-info text-white d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 70px; height: 70px; border-radius: 50%;">
                                @php
                                    $adminImage = auth()->user()->image;
                                    $isAdminImageExists = $adminImage && (str_starts_with($adminImage, 'http') || Storage::disk('public')->exists($adminImage));
                                @endphp
                                @if($isAdminImageExists)
                                    <img src="{{ auth()->user()->image_url }}" alt="Face 1" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <h3 class="mb-0 text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</h3>
                                @endif
                            </div>
                            <div class="ms-3 name overflow-hidden">
                                <h5 class="font-bold text-sm text-truncate mb-1">{{ auth()->user()->name }}</h5>
                                <h6 class="text-muted mb-0 text-xs text-truncate">{{ auth()->user()->email }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Users</h4>
                    </div>
                    <div class="card-content pb-4">
                        @foreach($data['recentUsers'] as $rUser)
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg bg-info text-white d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="min-width: 50px; height: 50px; border-radius: 50%;">
                                    @php
                                        $userImage = $rUser->image;
                                        $isUserImageExists = $userImage && (str_starts_with($userImage, 'http') || Storage::disk('public')->exists($userImage));
                                    @endphp
                                    @if($isUserImageExists)
                                        <img src="{{ $rUser->image_url }}" alt="AV" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="font-bold">{{ strtoupper(substr($rUser->name ?? 'U', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="name ms-4 overflow-hidden">
                                    <h5 class="mb-1 text-sm text-truncate">{{ $rUser->name }}</h5>
                                    <h6 class="text-muted mb-0 text-xs text-truncate">{{ $rUser->email }}</h6>
                                </div>
                            </div>
                        @endforeach
                        <div class="px-4">
                            <a href="{{ route('admin.users.index') }}" class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>View All Users</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Engagement Overview</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-visitors-profile"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        // Simple override of hardcoded chart data
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof optionsProfileVisit !== 'undefined') {
                optionsProfileVisit.series[0].data = @json($data['visitsData']);
                optionsProfileVisit.series[0].name = 'Registrations';
                chartProfileVisit.updateOptions(optionsProfileVisit);
            }

            if (typeof optionsVisitorsProfile !== 'undefined') {
                @php
                    $labels = $data['countryDistribution']->pluck('country');
                    $values = $data['countryDistribution']->pluck('total');
                @endphp
                optionsVisitorsProfile.series = @json($values);
                optionsVisitorsProfile.labels = @json($labels);
                chartVisitorsProfile.updateOptions(optionsVisitorsProfile);
            }

            // Country Sparklines
            const countryChartColors = ['#435ebe', '#198754', '#dc3545', '#ffc107', '#0dcaf0'];
            @foreach($data['countryDistribution'] as $index => $dist)
                (function() {
                    const selector = "#chart-country-{{ $index }}";
                    if (document.querySelector(selector)) {
                        const options = {
                            series: [{
                                name: 'Activity',
                                data: [{{ rand(10,50) }}, {{ rand(30,80) }}, {{ rand(20,60) }}, {{ rand(40,90) }}, {{ rand(30,70) }}, {{ rand(50,100) }}, {{ rand(40,80) }}, {{ rand(60,110) }}, {{ rand(50,90) }}, {{ rand(70,120) }}, {{ rand(60,100) }}, {{ rand(80,130) }}]
                            }],
                            chart: {
                                type: 'area',
                                height: 40,
                                sparkline: { enabled: true },
                            },
                            stroke: { curve: 'smooth', width: 2 },
                            colors: [countryChartColors[{{ $index % 5 }}]],
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.3,
                                    opacityTo: 0.8,
                                    stops: [0, 90, 100]
                                }
                            },
                            tooltip: { enabled: false }
                        };
                        new ApexCharts(document.querySelector(selector), options).render();
                    }
                })();
            @endforeach
        });
    </script>
@endpush
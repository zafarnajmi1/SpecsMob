@extends('admin-views.layouts.admin')

@section('title', 'Content Analytics')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Content Analytics</h3>
                    <p class="text-subtitle text-muted">Analyzing the performance of news, articles, and reviews.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Content Analytics</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                {{-- Top Articles by Views --}}
                <div class="col-md-6 col-12">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Most Viewed News/Articles</h4>
                            <span class="badge bg-primary">Top 20</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Content</th>
                                            <th class="text-end">Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_articles as $art)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-truncate" style="max-width: 300px;">
                                                        {{ $art->title }}</div>
                                                    <small class="text-muted">{{ ucfirst($art->type) }} •
                                                        {{ $art->published_at ? $art->published_at->format('M d, Y') : 'Draft' }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="fw-extrabold">{{ number_format($art->views_count) }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Reviews by Views --}}
                <div class="col-md-6 col-12">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Most Popular Reviews</h4>
                            <span class="badge bg-info">Top 20</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Review</th>
                                            <th class="text-end">Views</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top_reviews as $rev)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-truncate" style="max-width: 300px;">
                                                        {{ $rev->title }}</div>
                                                    <small class="text-muted">{{ $rev->device->name ?? 'System' }}
                                                        Review</small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="fw-extrabold text-info">{{ number_format($rev->views_count) }}
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

            <div class="row mt-4">
                {{-- Most Discussed --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title mb-0"><i class="bi bi-chat-dots me-2"></i>Most Discussed Content</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($discussed_articles as $art)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="p-3 border rounded h-100 bg-light-secondary position-relative">
                                            <h6 class="text-truncate pe-5">{{ $art->title }}</h6>
                                            <div class="position-absolute top-0 end-0 p-3">
                                                <span class="badge bg-secondary rounded-pill">
                                                    {{ number_format($art->comments_count) }} <i
                                                        class="bi bi-chat-fill ms-1"></i>
                                                </span>
                                            </div>
                                            <small class="text-muted">{{ ucfirst($art->type) }} • Discussed by users</small>
                                        </div>
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
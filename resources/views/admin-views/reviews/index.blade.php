@extends('admin-views.layouts.admin')
@section('title', 'Reviews List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Reviews</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reviews</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Reviews List</h4>
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Review
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Review</th>
                            <th>Brand & Device</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg me-3">
                                            <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}"
                                                style="width: 80px; height: 50px; border-radius: 4px; object-fit: cover;">
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $review->title }}</h6>
                                            <small
                                                class="text-muted">{{ Str::limit(strip_tags($review->body), 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-light-primary text-primary">{{ $review->device->brand->name ?? 'N/A' }}</span>
                                    <div class="text-sm mt-1">{{ $review->device->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $review->author->name ?? 'System' }}</div>
                                </td>
                                <td>
                                    @if($review->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            onsubmit="return confirm('Archive/Delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">No reviews found. <a
                                        href="{{ route('admin.reviews.create') }}">Create one now</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
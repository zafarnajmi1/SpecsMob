@extends('admin-views.layouts.admin')
@section('title', 'Reviews List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
    <style>
        .review-thumbnail {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .review-thumbnail:hover {
            transform: scale(1.05);
        }

        .dataTable-table td {
            vertical-align: middle !important;
        }

        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }

        .badge-published {
            background: linear-gradient(135deg, #43d39e 0%, #25c799 100%);
            color: white;
        }

        .badge-draft {
            background: linear-gradient(135deg, #ff7976 0%, #dc3545 100%);
            color: white;
        }
    </style>
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
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Thumbnail</th>
                            <th>Review Title</th>
                            <th>Brand & Device</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <!-- Thumbnail -->
                                <td>
                                    @if($review->cover_image_url)
                                        <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}" class="review-thumbnail">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center review-thumbnail">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Title -->
                                <td>
                                    <h6 class="mb-0">{{ $review->title }}</h6>
                                </td>

                                <!-- Brand & Device -->
                                <td>
                                    <span class="badge bg-light-primary text-primary">{{ $review->device->brand->name ?? 'N/A' }}</span>
                                    <div class="text-sm mt-1">{{ $review->device->name ?? 'N/A' }}</div>
                                </td>

                                <!-- Author -->
                                <td>
                                    <div class="fw-bold">{{ $review->author->name ?? 'System' }}</div>
                                </td>

                                <!-- Status -->
                                <td>
                                    @if($review->is_published)
                                        <span class="badge badge-published">Published</span>
                                    @else
                                        <span class="badge badge-draft">Draft</span>
                                    @endif
                                </td>

                                <!-- Created -->
                                <td>{{ $review->created_at->format('M d, Y') }}</td>

                                <!-- Actions -->
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Archive/Delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            {{-- This will likely be handled by datatables normally, but for server side or empty state: --}}
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        let table1 = document.querySelector('#table1');
        if(table1) {
            new simpleDatatables.DataTable(table1);
        }
    </script>
@endpush
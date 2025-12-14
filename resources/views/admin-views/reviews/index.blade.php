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
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Cover Image</th>
                            <th>Body</th>
                            <th>author_id</th>
                            <th>is_published</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                {{-- No. --}}
                                <td>{{ $loop->iteration }}</td>

                                {{-- Title --}}
                                <td>{{ $review->title }}</td>

                                {{-- Slug --}}
                                <td>{{ $review->slug }}</td>

                                {{-- Cover Image --}}
                                <td>
                                    @if($review->cover_image_url)
                                        <img src="{{ asset( $review->cover_image_url) }}" alt="{{ $review->title }}"
                                            width="60" height="60" style="object-fit: cover; border-radius:8px;">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                            style="width:60px; height:60px; border-radius:8px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Body (short preview) --}}
                                <td style="max-width: 250px; font-size: 12px;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($review->body), 80) }}
                                </td>

                                {{-- author_id (or author name if you eager-load relation) --}}
                                <td>
                                    @if($review->author)
                                        {{ $review->author->name }}
                                    @else
                                        {{ $review->author_id ?? '—' }}
                                    @endif
                                </td>

                                {{-- is_published --}}
                                <td>
                                    @if($review->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-danger">Unpublished</span>
                                    @endif
                                </td>

                                {{-- Created At --}}
                                <td>{{ $review->created_at->format('M d, Y') }}</td>

                                {{-- Actions --}}
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm confirm-delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    No reviews found.
                                    <a href="{{ route('admin.reviews.create') }}">Create the first review</a>
                                </td>
                            </tr>
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
        new simpleDatatables.DataTable(table1);
    </script>
@endpush
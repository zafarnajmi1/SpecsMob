@extends('admin-views.layouts.admin')
@section('title', 'Edit Review')

@push('styles')
    <style>
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f1f1;
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            color: #435ebe !important;
            margin: 0;
        }

        .sticky-action-bar {
            position: sticky;
            bottom: 0px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            margin-left: -2rem;
            margin-right: -2rem;
            margin-bottom: -1rem;
            border-top: 1px solid #eee;
            z-index: 1000;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-radius: 0 0 15px 15px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
        }

        .btn-primary {
            background-color: #435ebe;
            border-color: #435ebe;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-light-secondary {
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Review</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                            <li class="breadcrumb-item active">Edit Review</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Review Information</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('admin.reviews.update', $review->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                @include('admin-views.reviews.partials._form')

                                <!-- Include SEO Fields -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        @include('components.seo-fields', ['model' => $review ?? null])
                                    </div>
                                </div>

                                <div class="sticky-action-bar">
                                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-light-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i> Update Review
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
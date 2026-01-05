@extends('admin-views.layouts.admin')
@section('title', 'Add Home Review Slider')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add New Home Review Slider</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.homereview-slider.index') }}">Home Review
                                    Sliders</a></li>
                            <li class="breadcrumb-item active">Add Slider</li>
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
                            <h4 class="card-title">Slider Information</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('admin.homereview-slider.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="review_link" class="form-label">Review Link <span
                                                    class="text-danger">*</span></label>
                                            <input type="url" id="review_link" name="review_link"
                                                class="form-control @error('review_link') is-invalid @enderror"
                                                placeholder="Enter review link (e.g., https://example.com)"
                                                value="{{ old('review_link') }}" required>
                                            @error('review_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Image <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                id="image" name="image" accept="image/*" required>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg"></i> Save Slider
                                        </button>
                                        <a href="{{ route('admin.homereview-slider.index') }}"
                                            class="btn btn-light-secondary">
                                            <i class="bi bi-arrow-left"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
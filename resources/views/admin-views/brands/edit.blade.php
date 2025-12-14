@extends('admin-views.layouts.admin')
@section('title', 'Edit Brand')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Brand</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                            <li class="breadcrumb-item active">Edit Brand</li>
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
                            <h4 class="card-title">Brand Information</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('admin.brands.update', $brand->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Brand Name Field -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Brand Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter brand name" value="{{ old('name', $brand->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status Field -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                                name="status">
                                                <option value="1" {{ old('status', $brand->status) == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ old('status', $brand->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Image Upload (Logo and Cover) -->
                                    <div class="col-md-6 col-12">
                                        <x-image-upload fieldName="logo" :existingImage="$brand->logo ?? null" />
                                       </div>

                                    <div class="col-md-6 col-12">
                                          <x-image-upload fieldName="cover_image" :existingImage="$brand->cover_img ?? null" />
                                    </div>

                                    <!-- Description Field -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description" rows="4"
                                                placeholder="Enter brand description">{{ old('description', $brand->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg"></i> Update Brand
                                        </button>
                                        <a href="{{ route('admin.brands.index') }}" class="btn btn-light-secondary">
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

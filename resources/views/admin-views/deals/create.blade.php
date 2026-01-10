@extends('admin-views.layouts.admin')

@section('title', 'Create Deal')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Create New Deal</h3>
                    <p class="text-subtitle text-muted">Add a new deal or special offer.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.deals.index') }}">Deals & Offers</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Deal Information</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.deals.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Title -->
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Deal Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="E.g., Samsung Galaxy S24 Ultra - Best Price">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Device -->
                            <div class="col-md-6 mb-3">
                                <label for="device_id" class="form-label">Device <span class="text-danger">*</span></label>
                                <select class="form-select @error('device_id') is-invalid @enderror" id="device_id"
                                    name="device_id">
                                    <option value="">-- Select a Device --</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}"
                                            {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                            {{ $device->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('device_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Store Name -->
                            <div class="col-md-6 mb-3">
                                <label for="store_name" class="form-label">Store Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('store_name') is-invalid @enderror"
                                    id="store_name" name="store_name" value="{{ old('store_name') }}"
                                    placeholder="E.g., Amazon, Best Buy, etc.">
                                @error('store_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Region -->
                            <div class="col-md-6 mb-3">
                                <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('region') is-invalid @enderror"
                                    id="region" name="region" value="{{ old('region') }}"
                                    placeholder="E.g., USA, UK, EU, Asia">
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deal Link -->
                            <div class="col-md-6 mb-3">
                                <label for="link" class="form-label">Deal Link <span class="text-danger">*</span></label>
                                <input type="url" class="form-control @error('link') is-invalid @enderror"
                                    id="link" name="link" value="{{ old('link') }}"
                                    placeholder="https://example.com/deal">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Price -->
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Current Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror" id="price"
                                    name="price" value="{{ old('price') }}" placeholder="999.99">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Original Price -->
                            <div class="col-md-6 mb-3">
                                <label for="original_price" class="form-label">Original Price</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('original_price') is-invalid @enderror"
                                    id="original_price" name="original_price" value="{{ old('original_price') }}"
                                    placeholder="1299.99" onchange="calculateDiscount()">
                                @error('original_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Discount Percent -->
                            <div class="col-md-6 mb-3">
                                <label for="discount_percent" class="form-label">Discount Percent (%)</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('discount_percent') is-invalid @enderror"
                                    id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}"
                                    placeholder="20.5">
                                @error('discount_percent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Memory -->
                            <div class="col-md-6 mb-3">
                                <label for="memory" class="form-label">Memory / Variant</label>
                                <input type="text"
                                    class="form-control @error('memory') is-invalid @enderror" id="memory"
                                    name="memory" value="{{ old('memory') }}"
                                    placeholder="E.g., 12GB+256GB, 512GB">
                                @error('memory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image URL -->
                            <div class="col-md-6 mb-3">
                                <label for="image_url" class="form-label">Image URL</label>
                                <input type="url"
                                    class="form-control @error('image_url') is-invalid @enderror" id="image_url"
                                    name="image_url" value="{{ old('image_url') }}"
                                    placeholder="https://example.com/image.jpg">
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="Add any additional details about this deal...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Mark as Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mb-3 mt-4">
                            <a href="{{ route('admin.deals.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Create Deal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        function calculateDiscount() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;

            if (price > 0 && originalPrice > 0) {
                const discount = ((originalPrice - price) / originalPrice * 100).toFixed(2);
                document.getElementById('discount_percent').value = discount;
            }
        }
    </script>
@endsection

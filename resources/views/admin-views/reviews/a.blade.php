@extends('admin-views.layouts.admin')
@section('title', 'Add New Device')
@push('styles')
  <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background: #e7f1ff;
        }

        .upload-placeholder {
            color: #6c757d;
        }

        .preview-area {
            transition: all 0.3s ease;
        }

        .preview-container {
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header.bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1) !important;
            border-bottom: 1px solid rgba(13, 110, 253, 0.2);
        }
        .category-section {
    background: #f8f9fa;
    border-left: 4px solid #007bff !important;
}

.category-title input {
    font-weight: 600;
    font-size: 1.1rem;
    border: 1px dashed #ccc;
    background: transparent;
}

.category-title input:focus {
    border-color: #007bff;
    background: white;
}

.field-group {
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s;
}

.field-group:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.remove-category, .remove-field {
    opacity: 0.7;
    transition: opacity 0.2s;
}

.remove-category:hover, .remove-field:hover {
    opacity: 1;
}



.variant-section {
    background: #f8f9fa;
    border-left: 4px solid #28a745 !important;
    transition: all 0.3s ease;
}

.variant-section:hover {
    background: #e9ecef;
}

.variant-section:has(.is-primary:checked) {
    border-left-color: #007bff !important;
    background: #e7f3ff;
}

.remove-variant {
    opacity: 0.8;
    transition: opacity 0.2s;
}

.remove-variant:hover {
    opacity: 1;
}


/* Summernote customization */
.note-editor.note-frame {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.note-editor.note-frame .note-toolbar {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.375rem 0.375rem 0 0;
}

.note-editor.note-frame .note-statusbar {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 0.375rem 0.375rem;
}

.note-editor.note-frame .note-editing-area .note-editable {
    color: #212529;
    font-family: inherit;
    line-height: 1.5;
}

.note-btn-group .note-btn {
    background: white;
    border-color: #dee2e6;
}

.note-btn-group .note-btn:hover {
    background-color: #e9ecef;
}

/* Review section styling */
.review-section {
    transition: all 0.3s ease;
}

.review-section:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.move-section-up, .move-section-down {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add New Device</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.devices.index') }}">Devices</a></li>
                            <li class="breadcrumb-item active">Add Device</li>
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
                            <h4 class="card-title">Device Information</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('admin.devices.store') }}" method="POST"
                                enctype="multipart/form-data" id="deviceForm">
                                @csrf
                                
                                <!-- Section 1: Basic Information & Thumbnail -->
                                <div class="row mb-4">
    <div class="col-md-8 col-12">
        <div class="card">
            <div class="card-header bg-light-primary">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-info-circle me-2"></i>Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
                            <select class="form-select" id="brand_id" name="brand_id" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="device_type_id" class="form-label">Device Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="device_type_id" name="device_type_id" required>
                                <option value="">Select Type</option>
                                @foreach($deviceTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('device_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="form-label">Device Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" placeholder="e.g., Apple iPhone 17 Pro Max" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="form-label">Summary</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter device summary">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="announcement_date" class="form-label">Announcement Date</label>
                            <input type="date" id="announcement_date" class="form-control" name="announcement_date" value="{{ old('announcement_date') }}">
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="release_status" class="form-label">Release Status</label>
                            <select class="form-select" id="release_status" name="release_status">
                                <option value="">Select Status</option>
                                <option value="rumored" {{ old('release_status') == 'rumored' ? 'selected' : '' }}>Rumored</option>
                                <option value="announced" {{ old('release_status') == 'announced' ? 'selected' : '' }}>Announced</option>
                                <option value="released" {{ old('release_status') == 'released' ? 'selected' : '' }}>Released</option>
                                <option value="discontinued" {{ old('release_status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="released_at" class="form-label">Release Date</label>
                            <input type="date" id="released_at" class="form-control" name="released_at" value="{{ old('released_at') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header bg-light-primary">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-image me-2"></i>Device Thumbnail
                </h5>
            </div>
            <div class="card-body text-center">
                <x-image-upload fieldName="thumbnail" :existingImage="null" />
            </div>
        </div>
    </div>
</div>

                                <!-- Section 2: Quick Summary Fields -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-card-text me-2"></i>Quick Summary Fields
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="os_short" class="form-label">Operating
                                                                System</label>
                                                            <input type="text" id="os_short"
                                                                class="form-control @error('os_short') is-invalid @enderror"
                                                                placeholder="e.g., iOS 26, up to iOS 26.1" name="os_short"
                                                                value="{{ old('os_short') }}">
                                                            @error('os_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="chipset_short" class="form-label">Chipset</label>
                                                            <input type="text" id="chipset_short"
                                                                class="form-control @error('chipset_short') is-invalid @enderror"
                                                                placeholder="e.g., Apple A19 Pro" name="chipset_short"
                                                                value="{{ old('chipset_short') }}">
                                                            @error('chipset_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="storage_short" class="form-label">Storage</label>
                                                            <input type="text" id="storage_short"
                                                                class="form-control @error('storage_short') is-invalid @enderror"
                                                                placeholder="e.g., 256GB/512GB/2TB, no card slot"
                                                                name="storage_short" value="{{ old('storage_short') }}">
                                                            @error('storage_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="ram_short" class="form-label">RAM</label>
                                                            <input type="text" id="ram_short"
                                                                class="form-control @error('ram_short') is-invalid @enderror"
                                                                placeholder="e.g., 12GB RAM" name="ram_short"
                                                                value="{{ old('ram_short') }}">
                                                            @error('ram_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="main_camera_short" class="form-label">Main
                                                                Camera</label>
                                                            <input type="text" id="main_camera_short"
                                                                class="form-control @error('main_camera_short') is-invalid @enderror"
                                                                placeholder="e.g., 48 MP" name="main_camera_short"
                                                                value="{{ old('main_camera_short') }}">
                                                            @error('main_camera_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="battery_short" class="form-label">Battery</label>
                                                            <input type="text" id="battery_short"
                                                                class="form-control @error('battery_short') is-invalid @enderror"
                                                                placeholder="e.g., 4823 mAh, PD3.2 25W" name="battery_short"
                                                                value="{{ old('battery_short') }}">
                                                            @error('battery_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="color_short" class="form-label">Choose Color</label>

                                                            <div class="d-flex align-items-center gap-2">
                                                                <!-- Color Picker -->
                                                                <input type="color" id="color_picker"
                                                                    class="form-control form-control-color" value="#000000">

                                                                <!-- Text Display (auto-filled) -->
                                                                <input type="text" id="color_short" name="color_short"
                                                                    class="form-control @error('color_short') is-invalid @enderror"
                                                                    placeholder="Color Name">
                                                            </div>

                                                            @error('color_short')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="weight_grams" class="form-label">Weight
                                                                (grams)</label>
                                                            <input type="number" step="0.01" id="weight_grams"
                                                                class="form-control @error('weight_grams') is-invalid @enderror"
                                                                placeholder="e.g., 233.00" name="weight_grams"
                                                                value="{{ old('weight_grams') }}">
                                                            @error('weight_grams')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="dimensions" class="form-label">Dimensions</label>
                                                            <input type="text" id="dimensions"
                                                                class="form-control @error('dimensions') is-invalid @enderror"
                                                                placeholder="e.g., 163.4 x 78 x 8.8 mm" name="dimensions"
                                                                value="{{ old('dimensions') }}">
                                                            @error('dimensions')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                               <!-- Section 3: Device Specifications -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-list-check me-2"></i>Device Specifications
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addSpecCategory">
                    <i class="bi bi-plus-circle me-1"></i> Add Category
                </button>
            </div>
            <div class="card-body">
                <div id="specificationsContainer">
                    <!-- Existing specifications will be loaded here -->
                    @php
                        $defaultCategories = [
                            'Network' => ['Technology', '2G bands', '3G bands', '4G bands', 'Speed'],
                            // 'Launch' => ['Announced', 'Status'],
                            // 'Body' => ['Dimensions', 'Weight', 'Build', 'SIM', 'Other Features'],
                            // 'Display' => ['Type', 'Size', 'Resolution', 'Protection', 'Other Features'],
                            // 'Platform' => ['OS', 'Chipset', 'CPU', 'GPU'],
                            // 'Memory' => ['Card slot', 'Internal'],
                            // 'Main Camera' => ['Modules', 'Features', 'Video'],
                            // 'Selfie Camera' => ['Modules', 'Features', 'Video'],
                            // 'Sound' => ['Loudspeaker', '3.5mm jack', 'Other Features'],
                            // 'Comms' => ['WLAN', 'Bluetooth', 'Positioning', 'NFC', 'Radio', 'USB'],
                            // 'Features' => ['Sensors', 'Other'],
                            // 'Battery' => ['Type', 'Charging', 'Other'],
                            // 'Misc' => ['Colors', 'Models', 'SAR', 'Price']
                        ];
                    @endphp

                    @foreach($defaultCategories as $categoryName => $fields)
                    <div class="category-section mb-4 p-3 border rounded" data-category="{{ \Illuminate\Support\Str::slug($categoryName) }}">    <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="category-title mb-0 text-dark">
                                <input type="text" 
                                    class="form-control category-name" 
                                    name="spec_categories[{{ $loop->index }}][name]" 
                                    value="{{ $categoryName }}"
                                    placeholder="Category Name">
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-category">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <div class="fields-container">
                            @foreach($fields as $fieldIndex => $fieldLabel)
                            <div class="field-group row g-2 mb-2" data-field-index="{{ $fieldIndex }}">
                                <div class="col-md-4">
                                    <input type="text" 
                                        class="form-control field-label" 
                                        name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][label]" 
                                        value="{{ $fieldLabel }}"
                                        placeholder="Field Label">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select field-type" 
                                        name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][type]">
                                        <option value="string">Text</option>
                                        <option value="text">Long Text</option>
                                        <option value="number">Number</option>
                                        <option value="boolean">Yes/No</option>
                                        <option value="json">Multiple Values</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" 
                                        class="form-control field-value" 
                                        name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][value]" 
                                        placeholder="Enter value"
                                        value="{{ old("spec_categories.{$loop->parent->index}.fields.{$fieldIndex}.value") }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex gap-1">
                                        <div class="form-check form-switch pt-1">
                                            <input class="form-check-input filterable-check" 
                                                type="checkbox"
                                                name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][filterable]"
                                                value="1">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-field">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-secondary add-field mt-2">
                            <i class="bi bi-plus me-1"></i> Add Field
                        </button>
                    </div>
                    @endforeach
                </div>

                <template id="categoryTemplate">
                    <div class="category-section mb-4 p-3 border rounded" data-category="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="category-title mb-0 text-dark">
                                <input type="text" class="form-control category-name" name="" value="" placeholder="Category Name">
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-category">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="fields-container">
                            <!-- Fields will be added here -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary add-field mt-2">
                            <i class="bi bi-plus me-1"></i> Add Field
                        </button>
                    </div>
                </template>

                <template id="fieldTemplate">
                    <div class="field-group row g-2 mb-2" data-field-index="">
                        <div class="col-md-4">
                            <input type="text" class="form-control field-label" name="" value="" placeholder="Field Label">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select field-type" name="">
                                <option value="string">Text</option>
                                <option value="text">Long Text</option>
                                <option value="number">Number</option>
                                <option value="boolean">Yes/No</option>
                                <option value="json">Multiple Values</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control field-value" name="" placeholder="Enter value">
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-1">
                                <div class="form-check form-switch pt-1">
                                    <input class="form-check-input filterable-check" type="checkbox" name="" value="1">
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-field">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>


<!-- Section 4: Device Variants & Pricing -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-layers me-2"></i>Device Variants & Pricing
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addVariant">
                    <i class="bi bi-plus-circle me-1"></i> Add Variant
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tip:</strong> Add different storage/RAM variants of your device. Each variant can have multiple pricing offers from different stores.
                </div>
                
                <div id="variantsContainer">
                    <!-- Default primary variant -->
                    <div class="variant-section mb-4 p-3 border rounded" data-variant-index="0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="variant-title mb-0 text-dark">
                                <span class="badge bg-primary me-2 variant-badge">Primary Variant</span>
                                <small class="text-muted">(This will be shown as the main variant)</small>
                            </h6>
                            <div class="form-check">
                                <input class="form-check-input primary-variant" type="radio" name="primary_variant" value="0" checked>
                                <label class="form-check-label">Primary</label>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Variant Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control variant-label" 
                                        name="variants[0][label]" 
                                        value="Base Model"
                                        placeholder="e.g., 128GB 8GB RAM"
                                        required>
                                    <small class="text-muted">How this variant appears to users</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">RAM (GB)</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="variants[0][ram_gb]" 
                                        placeholder="e.g., 8"
                                        min="1">
                                    <small class="text-muted">Optional</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Storage (GB)</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="variants[0][storage_gb]" 
                                        placeholder="e.g., 128"
                                        min="1">
                                    <small class="text-muted">Optional</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Model Code</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="variants[0][model_code]" 
                                        placeholder="e.g., SM-G998B">
                                    <small class="text-muted">Manufacturer's model code</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="variants[0][is_active]">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="text-muted">Show/hide this variant</small>
                                </div>
                            </div>
                        </div>

                        <!-- Offers Section for this Variant -->
                        <div class="offers-section mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-dark mb-0">
                                    <i class="bi bi-tag me-1"></i>Pricing & Offers
                                    <small class="text-muted ms-1">(Add prices from different stores)</small>
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary add-offer" data-variant-index="0">
                                    <i class="bi bi-plus me-1"></i> Add Price
                                </button>
                            </div>
                            
                            <div class="offers-container">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Templates -->
                <template id="variantTemplate">
                    <div class="variant-section mb-4 p-3 border rounded" data-variant-index="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="variant-title mb-0 text-dark">
                                <span class="badge bg-secondary me-2 variant-badge">Additional Variant</span>
                                <small class="text-muted">(Click "Set as Primary" to make this the main variant)</small>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check">
                                    <input class="form-check-input primary-variant" type="radio" name="primary_variant" value="">
                                    <label class="form-check-label">Set as Primary</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-variant" title="Remove this variant">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Variant Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control variant-label" 
                                        name="" 
                                        value=""
                                        placeholder="e.g., 256GB 12GB RAM"
                                        required>
                                    <small class="text-muted">How this variant appears to users</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">RAM (GB)</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="" 
                                        placeholder="e.g., 12"
                                        min="1">
                                    <small class="text-muted">Optional</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Storage (GB)</label>
                                    <input type="number" 
                                        class="form-control" 
                                        name="" 
                                        placeholder="e.g., 256"
                                        min="1">
                                    <small class="text-muted">Optional</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Model Code</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="" 
                                        placeholder="e.g., SM-G998B">
                                    <small class="text-muted">Manufacturer's model code</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="text-muted">Show/hide this variant</small>
                                </div>
                            </div>
                        </div>

                        <!-- Offers Section -->
                        <div class="offers-section mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-dark mb-0">
                                    <i class="bi bi-tag me-1"></i>Pricing & Offers
                                    <small class="text-muted ms-1">(Add prices from different stores)</small>
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-primary add-offer" data-variant-index="">
                                    <i class="bi bi-plus me-1"></i> Add Price
                                </button>
                            </div>
                            
                            <div class="offers-container">
                            </div>
                        </div>
                    </div>
                </template>

                <template id="offerTemplate">
                    <div class="offer-item border rounded p-3 mb-3 bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Store <span class="text-danger">*</span></label>
                                    <select class="form-select store-select" name="" required>
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Where this price is available</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form-select country-select" name="" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Price location</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select class="form-select currency-select" name="" required>
                                        <option value="">Select Currency</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->iso_code }} ({{ $currency->symbol }})</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Price currency</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control price-input" name="" placeholder="0.00" min="0" required>
                                    <small class="text-muted">Current price</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Product URL</label>
                                    <input type="url" class="form-control" name="" placeholder="https://...">
                                    <small class="text-muted">Affiliate/product link</small>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <small class="text-muted">Show/hide</small>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="" value="1" checked>
                                    <label class="form-check-label">In Stock</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="" value="1">
                                    <label class="form-check-label">Featured Offer</label>
                                    <small class="text-muted d-block">Show in highlighted section</small>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-offer">
                                    <i class="bi bi-x me-1"></i> Remove Price
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<!-- Section 5: Device Videos -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-play-circle me-2"></i>Device Videos
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addVideo">
                    <i class="bi bi-plus-circle me-1"></i> Add Video
                </button>
            </div>
            <div class="card-body">
                <div id="videosContainer">
                    <!-- Videos will be added here dynamically -->
                    <div class="text-center py-4 text-muted" id="noVideosMessage">
                        <i class="bi bi-play-circle display-4"></i>
                        <p class="mt-2 mb-0">No videos added yet</p>
                        <small>Click "Add Video" to add YouTube links or upload videos</small>
                    </div>
                </div>

                <!-- Video Template -->
                <template id="videoTemplate">
                    <div class="video-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Title</label>
                                        <input type="text" class="form-control video-title" name="videos[][title]" placeholder="Enter video title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">YouTube URL or ID</label>
                                        <input type="text" class="form-control youtube-url" name="videos[][youtube_id]" placeholder="e.g., https://youtube.com/watch?v=ABC123 or ABC123" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control video-description" name="videos[][description]" rows="2" placeholder="Optional video description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Preview</label>
                                        <div class="video-preview embed-responsive embed-responsive-16by9 bg-light rounded" style="min-height: 300px; position: relative; width: 100%;">
                                            <div class="embed-responsive-item d-flex align-items-center justify-content-center text-muted position-absolute top-0 left-0 h-100 w-100">
                                                <div class="text-center">
                                                    <i class="bi bi-play-circle display-4"></i>
                                                    <p class="mt-2 mb-0">Preview will appear here</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Options</label>
                                        <div class="d-flex gap-2 mb-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary preview-video">
                                                <i class="bi bi-eye me-1"></i> Preview
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-video">
                                                <i class="bi bi-trash me-1"></i> Remove
                                            </button>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="videos[][is_published]" value="1" checked>
                                            <label class="form-check-label">Publish this video</label>
                                        </div>
                                        <small class="text-muted d-block mt-2">Enter YouTube URL and click "Preview" to see the video</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>


<!-- Section 6: Device Images -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-images me-2"></i>Device Images
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addImageGroup">
                    <i class="bi bi-plus-circle me-1"></i> Add Image Category
                </button>
            </div>
            <div class="card-body">
                <div id="imageGroupsContainer">
                    <!-- Default Official Images group -->
                    <div class="image-group-section mb-4 p-3 border rounded" data-group-index="0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="group-title mb-0 text-dark">
                                <span class="badge bg-primary me-2">Official Images</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-group">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category Title</label>
                                    <input type="text" class="form-control group-title-input" name="image_groups[0][title]" value="Official Images" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category Type</label>
                                    <input type="text" class="form-control group-type-input" name="image_groups[0][group_type]" value="official" placeholder="e.g., official, leaks, renders" required>
                                    <small class="text-muted">Enter any category type you want</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="image_groups[0][order]" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Images in this group -->
                        <div class="images-section mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-muted mb-0">Images in this category</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary add-image" data-group-index="0">
                                    <i class="bi bi-plus me-1"></i> Add Image
                                </button>
                            </div>
                            
                            <div class="images-container" data-group-index="0">
                                <!-- Images will be added here -->
                                <div class="text-center py-3 text-muted">
                                    <i class="bi bi-image display-6"></i>
                                    <p class="mt-2 mb-0">No images added yet</p>
                                    <small>Click "Add Image" to upload photos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Templates -->
                <template id="imageGroupTemplate">
                    <div class="image-group-section mb-4 p-3 border rounded" data-group-index="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="group-title mb-0 text-dark">
                                <span class="badge bg-secondary me-2">Image Category</span>
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-group">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category Title</label>
                                    <input type="text" class="form-control group-title-input" name="image_groups[][title]" value="" placeholder="e.g., Our Photos, Leaks, Renders" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category Type</label>
                                    <input type="text" class="form-control group-type-input" name="image_groups[][group_type]" value="" placeholder="e.g., official, leaks, renders, unboxing" required>
                                    <small class="text-muted">Enter any category type you want</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="image_groups[][order]" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Images in this group -->
                        <div class="images-section mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-muted mb-0">Images in this category</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary add-image" data-group-index="">
                                    <i class="bi bi-plus me-1"></i> Add Image
                                </button>
                            </div>
                            
                            <div class="images-container" data-group-index="">
                                <div class="text-center py-3 text-muted">
                                    <i class="bi bi-image display-6"></i>
                                    <p class="mt-2 mb-0">No images added yet</p>
                                    <small>Click "Add Image" to upload photos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <template id="imageTemplate">
                    <div class="image-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">Image</label>
                                        <div class="image-upload-container">
                                            <!-- The image upload component will be inserted here dynamically -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Caption</label>
                                        <input type="text" class="form-control image-caption" name="image_groups[][images][][caption]" placeholder="Optional image caption">
                                        <label class="form-label mt-2">Display Order</label>
                                        <input type="number" class="form-control image-order" name="image_groups[][images][][order]" value="0" min="0">
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-image">
                                                <i class="bi bi-trash me-1"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>


<!-- Section 6: Device Reviews -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-info d-flex justify-content-between align-items-center">
                <h5 class="card-title text-info mb-0">
                    <i class="bi bi-star me-2"></i>Device Reviews
                </h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-info" id="addReviewSection">
                        <i class="bi bi-plus-circle me-1"></i> Add Section
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="reviewsContainer">
                    <div class="text-center py-4 text-muted" id="noReviewsMessage">
                        <i class="bi bi-star display-4"></i>
                        <p class="mt-2 mb-0">No review sections added yet</p>
                        <small>Click "Add Section" to add review content</small>
                    </div>
                </div>

                <!-- Review Section Template -->
                <template id="reviewSectionTemplate">
                    <div class="review-section card mb-4 border-info">
                        <div class="card-header bg-light-info d-flex justify-content-between align-items-center">
                            <h6 class="card-title text-info mb-0 section-title-display">New Section</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary move-section-up" title="Move Up">
                                    <i class="bi bi-arrow-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary move-section-down" title="Move Down">
                                    <i class="bi bi-arrow-down"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-review-section" title="Remove Section">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Section Title *</label>
                                        <input type="text" class="form-control section-title" name="review_sections[][title]" 
                                               placeholder="e.g., Introduction, specs, unboxing" required>
                                        <div class="form-text">This will appear in the reviews dropdown</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Section Slug *</label>
                                        <input type="text" class="form-control section-slug" name="review_sections[][slug]" 
                                               placeholder="e.g., introduction" required>
                                        <div class="form-text">URL-friendly version (auto-generated)</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Review Content *</label>
                                        <textarea class="form-control review-content" name="review_sections[][body]" 
                                                  placeholder="Write detailed review content here..."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control section-order" name="review_sections[][order]" 
                                               value="0" min="0">
                                        <div class="form-text">Lower numbers appear first</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="review_sections[][is_published]" value="1" checked>
                                        <label class="form-check-label">Publish this section</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>


<!-- Section 3: Publishing & Settings -->
                                 <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-gear me-2"></i>Publishing & Settings
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="is_published" name="is_published"
                                                                value="1" {{ old('is_published') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_published">
                                                                Published
                                                            </label>
                                                            <small class="text-muted d-block">
                                                                If enabled, the device is visible on the website.
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="allow_opinions" name="allow_opinions"
                                                                value="1" {{ old('allow_opinions', 1) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="allow_opinions">
                                                                Allow Opinions
                                                            </label>
                                                            <small class="text-muted d-block">
                                                                Enable user opinions/comments for this device.
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="allow_fans" name="allow_fans"
                                                                value="1" {{ old('allow_fans', 1) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="allow_fans">
                                                                Allow Fans
                                                            </label>
                                                            <small class="text-muted d-block">
                                                                Enable “Become a fan” for this device.
                                                            </small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                 <div class="row mb-4">
                                    <div class="col-12">
                                        <!-- Include SEO Fields -->
                                         @include('components.seo-fields', ['model' => null])
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg"></i> Create Device
                                        </button>
                                        <a href="{{ route('admin.devices.index') }}" class="btn btn-light-secondary">
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



@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    document.getElementById('color_picker').addEventListener('input', function () {
        document.getElementById('color_short').value = this.value;
    });
    
    document.addEventListener('DOMContentLoaded', function() {
    let categoryCounter = {{ count($defaultCategories) }};
    let fieldCounters = {};

    // Initialize field counters for existing categories
    document.querySelectorAll('.category-section').forEach(category => {
        const categorySlug = category.dataset.category;
        fieldCounters[categorySlug] = category.querySelectorAll('.field-group').length;
    });

    // Add new category
    document.getElementById('addSpecCategory').addEventListener('click', function() {
        const template = document.getElementById('categoryTemplate');
        const clone = template.content.cloneNode(true);
        const categorySection = clone.querySelector('.category-section');
        const newCategorySlug = 'category-' + categoryCounter;
        
        categorySection.dataset.category = newCategorySlug;
        fieldCounters[newCategorySlug] = 0;
        
        // Update category name input
        const categoryNameInput = categorySection.querySelector('.category-name');
        categoryNameInput.name = `spec_categories[${categoryCounter}][name]`;
        categoryNameInput.value = 'New Category';
        
        // Update add field button
        const addFieldBtn = categorySection.querySelector('.add-field');
        addFieldBtn.setAttribute('data-category', newCategorySlug);
        
        document.getElementById('specificationsContainer').appendChild(categorySection);
        categoryCounter++;
    });

    // Add field to category
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-field') || e.target.closest('.add-field')) {
            const addFieldBtn = e.target.classList.contains('add-field') ? e.target : e.target.closest('.add-field');
            const categorySlug = addFieldBtn.dataset.category || addFieldBtn.closest('.category-section').dataset.category;
            addFieldToCategory(categorySlug);
        }
    });

    // Remove category with confirmation
    document.addEventListener('click', function(e) {
        const removeCategoryBtn = e.target.closest('.remove-category');
        if (removeCategoryBtn) {
            if (!confirm('Are you sure you want to remove this category and all its fields?')) {
                return;
            }
            const categorySection = removeCategoryBtn.closest('.category-section');
            const categorySlug = categorySection.dataset.category;
            delete fieldCounters[categorySlug];
            categorySection.remove();
            return;
        }

        // Remove field
        const removeFieldBtn = e.target.closest('.remove-field');
        if (removeFieldBtn) {
            const fieldGroup = removeFieldBtn.closest('.field-group');
            fieldGroup.remove();
        }
    });

    function addFieldToCategory(categorySlug) {
        const categorySection = document.querySelector(`[data-category="${categorySlug}"]`);
        const fieldsContainer = categorySection.querySelector('.fields-container');
        const categoryIndex = Array.from(document.querySelectorAll('.category-section')).indexOf(categorySection);
        
        const template = document.getElementById('fieldTemplate');
        const clone = template.content.cloneNode(true);
        const fieldGroup = clone.querySelector('.field-group');
        
        const fieldIndex = fieldCounters[categorySlug]++;
        fieldGroup.dataset.fieldIndex = fieldIndex;
        
        // Update field inputs
        const baseName = `spec_categories[${categoryIndex}][fields][${fieldIndex}]`;
        
        fieldGroup.querySelector('.field-label').name = `${baseName}[label]`;
        fieldGroup.querySelector('.field-type').name = `${baseName}[type]`;
        fieldGroup.querySelector('.field-value').name = `${baseName}[value]`;
        fieldGroup.querySelector('.filterable-check').name = `${baseName}[filterable]`;
        
        fieldsContainer.appendChild(fieldGroup);
    }

    // Handle form submission - validate at least one category has fields
    document.getElementById('deviceForm').addEventListener('submit', function(e) {
        const hasSpecifications = document.querySelectorAll('.field-group').length > 0;
        if (!hasSpecifications) {
            if (!confirm('No specifications added. Continue without specifications?')) {
                e.preventDefault();
                return false;
            }
        }
        return true;
    });
});

</script>

<!-- Device Variants & Pricing -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let variantCounter = 1; // Start from 1 since 0 is used for primary
    let offerCounters = {0: 0}; // Track offers per variant

    // Add new variant
    document.getElementById('addVariant').addEventListener('click', function() {
        const template = document.getElementById('variantTemplate');
        const clone = template.content.cloneNode(true);
        const variantSection = clone.querySelector('.variant-section');
        
        variantSection.dataset.variantIndex = variantCounter;
        offerCounters[variantCounter] = 0;
        
        // Update all inputs and selects
        updateVariantInputs(variantSection, variantCounter);
        
        document.getElementById('variantsContainer').appendChild(variantSection);
        variantCounter++;
    });

    // Remove variant
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-variant');
        if (removeBtn) {
            if (!confirm('Are you sure you want to remove this variant and all its offers?')) {
                return;
            }
            const variantSection = removeBtn.closest('.variant-section');
            const variantIndex = variantSection.dataset.variantIndex;
            delete offerCounters[variantIndex];
            variantSection.remove();
            
            // If we removed the primary variant, set the first remaining variant as primary
            updatePrimaryVariant();
        }
    });

    // Handle primary variant selection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('primary-variant') && e.target.checked) {
            updatePrimaryVariantBadges();
        }
        
        // Update variant label preview
        if (e.target.classList.contains('variant-label')) {
            const variantSection = e.target.closest('.variant-section');
            const preview = variantSection.querySelector('.variant-label-preview');
            if (preview) {
                preview.value = e.target.value;
            }
        }
        
        // Auto-set currency based on country
        if (e.target.classList.contains('country-select')) {
            const offerItem = e.target.closest('.offer-item');
            const currencySelect = offerItem.querySelector('.currency-select');
            const countryId = e.target.value;
            
            const currencyMap = {
                '1': 'USD', // US -> USD
                '2': 'GBP', // UK -> GBP
                '3': 'EUR', // Germany -> EUR
            };
            
            if (currencyMap[countryId]) {
                const options = currencySelect.options;
                for (let i = 0; i < options.length; i++) {
                    if (options[i].text.includes(currencyMap[countryId])) {
                        currencySelect.value = options[i].value;
                        break;
                    }
                }
            }
        }
    });

    // Add offer to variant
    document.addEventListener('click', function(e) {
        const addOfferBtn = e.target.closest('.add-offer');
        if (addOfferBtn) {
            const variantIndex = addOfferBtn.dataset.variantIndex;
            addOfferToVariant(variantIndex);
        }
    });

    // Remove offer
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-offer');
        if (removeBtn) {
            const offerItem = removeBtn.closest('.offer-item');
            offerItem.remove();
        }
    });

    function updateVariantInputs(variantSection, variantIndex) {
        // Update variant inputs
        const inputs = variantSection.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.name) {
                const baseName = input.name.replace(/\[\d+\]/, `[${variantIndex}]`);
                input.name = baseName;
                
                // Update radio button value
                if (input.classList.contains('primary-variant')) {
                    input.value = variantIndex;
                }
            }
        });

        // Update add offer button
        const addOfferBtn = variantSection.querySelector('.add-offer');
        addOfferBtn.dataset.variantIndex = variantIndex;
        
        // Update variant badge
        const variantBadge = variantSection.querySelector('.variant-badge');
        if (variantBadge) {
            variantBadge.textContent = 'Variant';
            variantBadge.className = 'badge bg-secondary me-2 variant-badge';
        }
    }

    function updatePrimaryVariantBadges() {
        const variantSections = document.querySelectorAll('.variant-section');
        let primaryVariantIndex = null;
        
        // Find which variant is currently selected as primary
        variantSections.forEach(section => {
            const radio = section.querySelector('.primary-variant');
            if (radio && radio.checked) {
                primaryVariantIndex = radio.value;
            }
        });
        
        // Update all badges
        variantSections.forEach(section => {
            const variantBadge = section.querySelector('.variant-badge');
            const radio = section.querySelector('.primary-variant');
            
            if (variantBadge && radio) {
                if (radio.checked) {
                    variantBadge.textContent = 'Primary';
                    variantBadge.className = 'badge bg-primary me-2 variant-badge';
                } else {
                    variantBadge.textContent = 'Variant';
                    variantBadge.className = 'badge bg-secondary me-2 variant-badge';
                }
            }
        });
    }

    function updatePrimaryVariant() {
        // Set the first variant as primary if no primary is selected
        const primaryRadios = document.querySelectorAll('.primary-variant');
        let hasPrimary = false;
        
        primaryRadios.forEach(radio => {
            if (radio.checked) {
                hasPrimary = true;
            }
        });
        
        if (!hasPrimary && primaryRadios.length > 0) {
            primaryRadios[0].checked = true;
            updatePrimaryVariantBadges();
        }
    }

    function addOfferToVariant(variantIndex) {
        const variantSection = document.querySelector(`[data-variant-index="${variantIndex}"]`);
        const offersContainer = variantSection.querySelector('.offers-container');
        const offerIndex = offerCounters[variantIndex]++;
        
        const template = document.getElementById('offerTemplate');
        const clone = template.content.cloneNode(true);
        const offerItem = clone.querySelector('.offer-item');
        
        // Update offer inputs
        const baseName = `variants[${variantIndex}][offers][${offerIndex}]`;
        
        offerItem.querySelector('.store-select').name = `${baseName}[store_id]`;
        offerItem.querySelector('.country-select').name = `${baseName}[country_id]`;
        offerItem.querySelector('.currency-select').name = `${baseName}[currency_id]`;
        offerItem.querySelector('.price-input').name = `${baseName}[price]`;
        offerItem.querySelector('input[type="url"]').name = `${baseName}[url]`;
        offerItem.querySelector('input[type="checkbox"][value="1"]').name = `${baseName}[in_stock]`;
        offerItem.querySelectorAll('input[type="checkbox"]')[1].name = `${baseName}[is_featured]`;
        
        offersContainer.appendChild(offerItem);
    }

    // Initialize primary variant badges on page load
    updatePrimaryVariantBadges();
});
</script>

<!-- Device videos -->
 <script>
document.addEventListener('DOMContentLoaded', function() {
    let videoCounter = 0;

    // Add new video
    document.getElementById('addVideo').addEventListener('click', function() {
        const template = document.getElementById('videoTemplate');
        const clone = template.content.cloneNode(true);
        const videoItem = clone.querySelector('.video-item');
        
        // Update input names with index
        const inputs = videoItem.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('videos[]', `videos[${videoCounter}]`);
            }
        });

        document.getElementById('videosContainer').appendChild(videoItem);
        
        // Hide no videos message
        document.getElementById('noVideosMessage').style.display = 'none';
        
        videoCounter++;
    });

    // Remove video
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-video') || e.target.closest('.remove-video')) {
            const removeBtn = e.target.classList.contains('remove-video') ? e.target : e.target.closest('.remove-video');
            const videoItem = removeBtn.closest('.video-item');
            
            if (confirm('Are you sure you want to remove this video?')) {
                videoItem.remove();
                
                // Show no videos message if no videos left
                const videoItems = document.querySelectorAll('.video-item');
                if (videoItems.length === 0) {
                    document.getElementById('noVideosMessage').style.display = 'block';
                }
            }
        }
    });

    // Preview YouTube video
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('preview-video') || e.target.closest('.preview-video')) {
            const previewBtn = e.target.classList.contains('preview-video') ? e.target : e.target.closest('.preview-video');
            const videoItem = previewBtn.closest('.video-item');
            const youtubeUrl = videoItem.querySelector('.youtube-url').value;
            const previewContainer = videoItem.querySelector('.video-preview');
            
            if (!youtubeUrl) {
                alert('Please enter a YouTube URL or ID first');
                return;
            }

            const videoId = extractYouTubeId(youtubeUrl);
            if (!videoId) {
                alert('Please enter a valid YouTube URL or ID');
                return;
            }

            // Create iframe embed
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
            previewContainer.innerHTML = `
                <iframe 
                    src="${embedUrl}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; border-radius: 0.375rem;" 
                </iframe>
            `;
        }
    });

    // Auto-preview when YouTube URL is pasted
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('youtube-url')) {
            const youtubeUrl = e.target.value;
            if (youtubeUrl.length > 20) { // Only auto-preview for longer URLs
                const videoItem = e.target.closest('.video-item');
                const previewContainer = videoItem.querySelector('.video-preview');
                const videoId = extractYouTubeId(youtubeUrl);
                
                if (videoId) {
                    const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                    previewContainer.innerHTML = `
                        <iframe 
                            src="${embedUrl}" 
                            class="embed-responsive-item" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen
                            style="position:absolute; width: 100%; height: 100%; border-radius: 0.375rem;">
                        </iframe>
                    `;
                }
            }
        }
    });

    // Function to extract YouTube ID from various URL formats
    function extractYouTubeId(url) {
        if (!url) return null;
        
        // If it's already just an ID (11 characters)
        if (url.length === 11 && !url.includes('/') && !url.includes('?')) {
            return url;
        }
        
        // Handle various YouTube URL formats
        const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }
});
</script>

<!-- Device images by groups -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let groupCounter = 1;
    let imageCounters = {0: 0};

    // Add new image group
    document.getElementById('addImageGroup').addEventListener('click', function() {
        const template = document.getElementById('imageGroupTemplate');
        const clone = template.content.cloneNode(true);
        const groupSection = clone.querySelector('.image-group-section');
        
        groupSection.dataset.groupIndex = groupCounter;
        imageCounters[groupCounter] = 0;
        
        updateGroupInputs(groupSection, groupCounter);
        document.getElementById('imageGroupsContainer').appendChild(groupSection);
        groupCounter++;
    });

    // Remove image group
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-group')) {
            const removeBtn = e.target.closest('.remove-group');
            const groupSection = removeBtn.closest('.image-group-section');
            
            if (confirm('Are you sure you want to remove this image category and all its images?')) {
                const groupIndex = groupSection.dataset.groupIndex;
                delete imageCounters[groupIndex];
                groupSection.remove();
            }
        }
    });

    // Add image to group
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-image')) {
            const addBtn = e.target.closest('.add-image');
            const groupIndex = addBtn.dataset.groupIndex;
            addImageToGroup(groupIndex);
        }
    });

    // Remove image
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-image')) {
            const removeBtn = e.target.closest('.remove-image');
            const imageItem = removeBtn.closest('.image-item');
            
            if (confirm('Are you sure you want to remove this image?')) {
                imageItem.remove();
                checkEmptyImageContainer(removeBtn.closest('.images-container'));
            }
        }
    });

    function updateGroupInputs(groupSection, groupIndex) {
        const inputs = groupSection.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name && input.name.startsWith('image_groups[]')) {
                input.name = input.name.replace('image_groups[]', `image_groups[${groupIndex}]`);
            }
        });

        const addImageBtn = groupSection.querySelector('.add-image');
        addImageBtn.dataset.groupIndex = groupIndex;

        const imagesContainer = groupSection.querySelector('.images-container');
        imagesContainer.dataset.groupIndex = groupIndex;
    }

    function addImageToGroup(groupIndex) {
        const groupSection = document.querySelector(`[data-group-index="${groupIndex}"]`);
        const imagesContainer = groupSection.querySelector('.images-container');
        const imageIndex = imageCounters[groupIndex]++;
        
        const template = document.getElementById('imageTemplate');
        const clone = template.content.cloneNode(true);
        const imageItem = clone.querySelector('.image-item');
        
        // Remove empty state if it exists
        const emptyState = imagesContainer.querySelector('.text-center.text-muted');
        if (emptyState) {
            emptyState.remove();
        }
        
        // Update image inputs
        const baseName = `image_groups[${groupIndex}][images][${imageIndex}]`;
        
        imageItem.querySelector('.image-caption').name = `${baseName}[caption]`;
        imageItem.querySelector('.image-order').name = `${baseName}[order]`;
        
        // Create a unique field name
        const fieldName = `group_${groupIndex}_image_${imageIndex}`;
        
        // Create the image upload HTML manually (matching your component structure)
        const uploadContainer = imageItem.querySelector('.image-upload-container');
        uploadContainer.innerHTML = createImageUploadHTML(fieldName, baseName);
        
        imagesContainer.appendChild(imageItem);
        
        // Initialize the image upload functionality
        setTimeout(() => {
            initializeImageUpload(fieldName);
        }, 100);
    }

    function createImageUploadHTML(fieldName, baseName) {
        return `
            <div class="form-group">
                <div class="upload-area" id="${fieldName}UploadArea" style="display:flex;">
                    <div class="upload-placeholder">
                        <i class="bi bi-image display-4 text-muted"></i>
                        <p class="mt-2 mb-1 text-muted">Click to upload image</p>
                        <small class="text-muted">PNG, JPG up to 2MB</small>
                    </div>
                    <input type="file" class="form-control d-none" id="${fieldName}" name="${baseName}[image]" accept="image/*">
                </div>

                <div class="preview-area mt-2" id="${fieldName}PreviewArea" style="display:none;">
                    <div class="preview-container position-relative d-inline-block">
                        <img id="${fieldName}Preview" src="#" alt="${fieldName} preview" class="img-thumbnail rounded" style="max-width:150px; max-height:150px; object-fit:contain;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" id="remove${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)}">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function initializeImageUpload(fieldName) {
        const uploadArea = document.getElementById(fieldName + 'UploadArea');
        const previewArea = document.getElementById(fieldName + 'PreviewArea');
        const previewImage = document.getElementById(fieldName + 'Preview');
        const inputElement = document.getElementById(fieldName);
        const removeBtn = document.getElementById('remove' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1));

        if (!uploadArea || !previewArea || !previewImage || !inputElement || !removeBtn) return;

        function handleImageUpload(input, uploadArea, previewArea, previewImage) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file (PNG, JPG, JPEG)');
                    input.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    uploadArea.style.display = 'none';
                    previewArea.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Add event listeners
        uploadArea.addEventListener('click', function () {
            inputElement.click();
        });

        inputElement.addEventListener('change', function () {
            handleImageUpload(inputElement, uploadArea, previewArea, previewImage);
        });

        removeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            inputElement.value = '';
            previewArea.style.display = 'none';
            uploadArea.style.display = 'flex';
            previewImage.src = '';
        });

        previewImage.addEventListener('click', function () {
            inputElement.click();
        });
    }

    function checkEmptyImageContainer(container) {
        const imageItems = container.querySelectorAll('.image-item');
        if (imageItems.length === 0) {
            container.innerHTML = `
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-image display-6"></i>
                    <p class="mt-2 mb-0">No images added yet</p>
                    <small>Click "Add Image" to upload photos</small>
                </div>
            `;
        }
    }
});
</script>

<!-- Device review -->
 <script>
 // Reviews Section Management
$(document).ready(function() {
    const reviewsContainer = $('#reviewsContainer');
    const noReviewsMessage = $('#noReviewsMessage');
    const reviewSectionTemplate = $('#reviewSectionTemplate');
    const addReviewSectionBtn = $('#addReviewSection');
    
    let reviewSectionCount = 0;
    
    // Default review sections (similar to GSMArena)
    const defaultSections = [
        { title: 'Introduction, specs, unboxing', slug: 'introduction', order: 0 },
        { title: 'Design, build quality, handling', slug: 'design', order: 1 },
        { title: 'Lab tests - display, battery life, charging speed, speakers', slug: 'lab-tests', order: 2 },
        { title: 'Software', slug: 'software', order: 3 },
        { title: 'Performance, benchmarks, stress tests', slug: 'performance', order: 4 },
        { title: 'Camera', slug: 'camera', order: 5 },
        { title: 'Competition, verdict, pros and cons', slug: 'verdict', order: 6 }
    ];

    // Add new review section
    addReviewSectionBtn.on('click', function() {
        addReviewSection();
    });

    // Add default sections button
    const addDefaultSectionsBtn = $('<button>', {
        type: 'button',
        class: 'btn btn-sm btn-outline-secondary ms-2',
        html: '<i class="bi bi-magic me-1"></i> Add Default Sections'
    }).on('click', addDefaultSections);
    
    addReviewSectionBtn.after(addDefaultSectionsBtn);

    function addReviewSection(sectionData = null) {
        const template = reviewSectionTemplate.html();
        const reviewSection = $(template);
        
        // Update form field names with index
        const index = reviewSectionCount++;
        reviewSection.find('[name]').each(function() {
            const name = $(this).attr('name');
            $(this).attr('name', name.replace('[]', `[${index}]`));
        });

        // Set data if provided
        if (sectionData) {
            reviewSection.find('.section-title').val(sectionData.title || '');
            reviewSection.find('.section-slug').val(sectionData.slug || '');
            reviewSection.find('.section-order').val(sectionData.order || index);
            // Update display title
            reviewSection.find('.section-title-display').text(sectionData.title || 'New Section');
        }

        // Auto-generate slug from title
        reviewSection.find('.section-title').on('blur', function() {
            const slugInput = reviewSection.find('.section-slug');
            if (!slugInput.val()) {
                slugInput.val(generateSlug($(this).val()));
            }
            // Update section title display
            reviewSection.find('.section-title-display').text($(this).val() || 'New Section');
        });

        // Manual slug editing with validation
        reviewSection.find('.section-slug').on('input', function() {
            $(this).val(generateSlug($(this).val()));
        });

        // Remove section
        reviewSection.find('.remove-review-section').on('click', function() {
            if (reviewsContainer.find('.review-section').length === 1) {
                noReviewsMessage.show();
            }
            reviewSection.remove();
            updateSectionOrders();
        });

        // Move section up
        reviewSection.find('.move-section-up').on('click', function() {
            const prev = reviewSection.prev();
            if (prev.length && prev.hasClass('review-section')) {
                reviewSection.insertBefore(prev);
                updateSectionOrders();
            }
        });

        // Move section down
        reviewSection.find('.move-section-down').on('click', function() {
            const next = reviewSection.next();
            if (next.length && next.hasClass('review-section')) {
                reviewSection.insertAfter(next);
                updateSectionOrders();
            }
        });

        // Initialize Summernote for rich text editor
        reviewSection.find('.review-content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48'],
            callbacks: {
                onChange: function(contents) {
                    // Content is automatically saved to textarea
                },
                onPaste: function(e) {
                    // Clean up pasted content
                    const bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

        // Add to container
        noReviewsMessage.hide();
        reviewsContainer.append(reviewSection);
        
        updateSectionOrders();
    }

    function addDefaultSections() {
        if (confirm('Add all default review sections? This will add 7 common sections used in device reviews.')) {
            defaultSections.forEach(section => {
                addReviewSection(section);
            });
        }
    }

    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^\w\s-]+/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function updateSectionOrders() {
        reviewsContainer.find('.review-section').each(function(index) {
            $(this).find('.section-order').val(index);
        });
    }
});
 </script>

 <!-- Device SEO -->
<script src="{{ asset('admin/assets/js/seo-management.js') }}"></script>
 
@endpush
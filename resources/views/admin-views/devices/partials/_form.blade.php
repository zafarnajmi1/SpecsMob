@php
    /** @var \App\Models\Device|null $device */
    $isEdit = isset($device);
    $existingReview = $isEdit && $device->reviews->count() > 0
        ? $device->reviews->first()
        : null;
@endphp

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

        .remove-category,
        .remove-field {
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .remove-category:hover,
        .remove-field:hover {
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

        .move-section-up,
        .move-section-down {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

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
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $isEdit ? $device->brand_id : null) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="device_type_id" class="form-label">Device Type <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="device_type_id" name="device_type_id" required>
                                <option value="">Select Type</option>
                                @foreach($deviceTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('device_type_id', $isEdit ? $device->device_type_id : null) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('device_type_id')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="name" class="form-label">Device Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" name="name"
                                placeholder="e.g., Apple iPhone 17 Pro Max"
                                value="{{ old('name', $isEdit ? $device->name : null) }}" required>
                            @error('name')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" id="slug" class="form-control" name="slug"
                                placeholder="e.g., apple-iphone-17-pro-max"
                                value="{{ old('slug', $isEdit ? $device->slug : null) }}" required>
                            @error('slug')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="form-label">Summary</label>
                            <textarea class="form-control review-content" name="description" rows="2"
                                placeholder="Write Device summary here...">{{ old('description', $isEdit ? $device->description : null) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="announcement_date" class="form-label">Announcement Date</label>
                            <input type="date" id="announcement_date" class="form-control" name="announcement_date"
                                value="{{ old('announcement_date', $isEdit ? optional($device->announcement_date)->format('Y-m-d') : null) }}">
                            @error('announcement_date')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="release_status" class="form-label">Release Status</label>
                            @php
                                $releaseStatus = old('release_status', $isEdit ? $device->release_status : null);
                            @endphp
                            <select class="form-select" id="release_status" name="release_status">
                                <option value="">Select Status</option>
                                <option value="rumored" {{ $releaseStatus == 'rumored' ? 'selected' : '' }}>Rumored
                                </option>
                                <option value="announced" {{ $releaseStatus == 'announced' ? 'selected' : '' }}>Announced
                                </option>
                                <option value="released" {{ $releaseStatus == 'released' ? 'selected' : '' }}>Released
                                </option>
                                <option value="discontinued" {{ $releaseStatus == 'discontinued' ? 'selected' : '' }}>
                                    Discontinued</option>
                            </select>
                            @error('release_status')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="released_at" class="form-label">Release Date</label>
                            <input type="date" id="released_at" class="form-control" name="released_at"
                                value="{{ old('released_at', $isEdit ? optional($device->released_at)->format('Y-m-d') : null) }}">
                            @error('released_at')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Thumbnail --}}
    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header bg-light-primary">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-image me-2"></i>Device Thumbnail
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="form-group text-center">

                    <label class="form-label d-block">Device Thumbnail</label>
                    <x-image-upload fieldName="thumbnail" :existingImage="$isEdit ? $device->thumbnail_url : null" />
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Summary Fields --}}
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
                            <label for="os_short" class="form-label">Operating System</label>
                            <input type="text" id="os_short"
                                class="form-control @error('os_short') is-invalid @enderror" name="os_short"
                                placeholder="e.g., iOS 26, up to iOS 26.1"
                                value="{{ old('os_short', $isEdit ? $device->os_short : null) }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="chipset_short" class="form-label">Chipset</label>
                            <input type="text" id="chipset_short"
                                class="form-control @error('chipset_short') is-invalid @enderror" name="chipset_short"
                                placeholder="e.g., Apple A19 Pro"
                                value="{{ old('chipset_short', $isEdit ? $device->chipset_short : null) }}">
                            @error('chipset_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="storage_short" class="form-label">Storage</label>
                            <input type="text" id="storage_short"
                                class="form-control @error('storage_short') is-invalid @enderror" name="storage_short"
                                placeholder="e.g., 256GB/512GB/2TB, no card slot"
                                value="{{ old('storage_short', $isEdit ? $device->storage_short : null) }}">
                            @error('storage_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="ram_short" class="form-label">RAM</label>
                            <input type="text" id="ram_short"
                                class="form-control @error('ram_short') is-invalid @enderror" name="ram_short"
                                placeholder="e.g., 12GB RAM"
                                value="{{ old('ram_short', $isEdit ? $device->ram_short : null) }}">
                            @error('ram_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="main_camera_short" class="form-label">Main Camera</label>
                            <input type="text" id="main_camera_short"
                                class="form-control @error('main_camera_short') is-invalid @enderror"
                                name="main_camera_short" placeholder="e.g., 48 MP"
                                value="{{ old('main_camera_short', $isEdit ? $device->main_camera_short : null) }}">
                            @error('main_camera_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="battery_short" class="form-label">Battery</label>
                            <input type="text" id="battery_short"
                                class="form-control @error('battery_short') is-invalid @enderror" name="battery_short"
                                placeholder="e.g., 4823 mAh, PD3.2 25W"
                                value="{{ old('battery_short', $isEdit ? $device->battery_short : null) }}">
                            @error('battery_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        @php
                            $colorValue = old('color_short', $isEdit ? $device->color_short : '#000000');
                        @endphp
                        <div class="form-group">
                            <label for="color_short" class="form-label">Choose Color</label>

                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="color_picker" class="form-control form-control-color"
                                    value="{{ \Illuminate\Support\Str::startsWith($colorValue, '#') ? $colorValue : '#000000' }}">

                                <input type="text" id="color_short" name="color_short"
                                    class="form-control @error('color_short') is-invalid @enderror"
                                    placeholder="Color Value / Name" value="{{ $colorValue }}">
                            </div>

                            @error('color_short')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="weight_grams" class="form-label">Weight (grams)</label>
                            <input type="number" step="0.01" id="weight_grams"
                                class="form-control @error('weight_grams') is-invalid @enderror" name="weight_grams"
                                placeholder="e.g., 233.00"
                                value="{{ old('weight_grams', $isEdit ? $device->weight_grams : null) }}">
                            @error('weight_grams')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label for="dimensions" class="form-label">Dimensions</label>
                            <input type="text" id="dimensions"
                                class="form-control @error('dimensions') is-invalid @enderror" name="dimensions"
                                placeholder="e.g., 163.4 x 78 x 8.8 mm"
                                value="{{ old('dimensions', $isEdit ? $device->dimensions : null) }}">
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
                    @if($isEdit && $device->specValues->count() > 0)
                        <!-- Group specifications by category -->
                        @php
                            $categories = [];
                            foreach ($device->specValues as $specValue) {
                                $category = $specValue->field->category;
                                if (!isset($categories[$category->id])) {
                                    $categories[$category->id] = [
                                        'category' => $category,
                                        'fields' => []
                                    ];
                                }
                                $categories[$category->id]['fields'][] = $specValue;
                            }
                            $categoryIndex = 0;
                        @endphp

                        <!-- Show existing specifications for editing -->
                        @foreach($categories as $categoryData)
                            @php
                                $category = $categoryData['category'];
                                $fields = $categoryData['fields'];
                            @endphp
                            <div class="category-section mb-4 p-3 border rounded" data-category="{{ $category->slug }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="category-title mb-0 text-dark">
                                        <input type="text" class="form-control category-name"
                                            name="spec_categories[{{ $categoryIndex }}][name]" value="{{ $category->name }}"
                                            placeholder="Category Name">
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-category">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <div class="fields-container">
                                    @foreach($fields as $fieldIndex => $specValue)
                                        @php
                                            $field = $specValue->field;
                                            $value = '';
                                            switch ($field->type) {
                                                case 'number':
                                                    $value = $specValue->value_number;
                                                    break;
                                                case 'json':
                                                    $value = $specValue->value_json ? json_encode($specValue->value_json) : '';
                                                    break;
                                                case 'boolean':
                                                    $value = $specValue->value_string;
                                                    break;
                                                default:
                                                    $value = $specValue->value_string;
                                            }
                                        @endphp
                                        <div class="field-group row g-2 mb-2" data-field-index="{{ $fieldIndex }}">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control field-label"
                                                    name="spec_categories[{{ $categoryIndex }}][fields][{{ $fieldIndex }}][label]"
                                                    value="{{ $field->label }}" placeholder="Field Label">
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-select field-type"
                                                    name="spec_categories[{{ $categoryIndex }}][fields][{{ $fieldIndex }}][type]">
                                                    <option value="string" {{ $field->type == 'string' ? 'selected' : '' }}>Text
                                                    </option>
                                                    <option value="text" {{ $field->type == 'text' ? 'selected' : '' }}>Long Text
                                                    </option>
                                                    <option value="number" {{ $field->type == 'number' ? 'selected' : '' }}>Number
                                                    </option>
                                                    <option value="boolean" {{ $field->type == 'boolean' ? 'selected' : '' }}>Yes/No
                                                    </option>
                                                    <option value="json" {{ $field->type == 'json' ? 'selected' : '' }}>Multiple
                                                        Values</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control field-value"
                                                    name="spec_categories[{{ $categoryIndex }}][fields][{{ $fieldIndex }}][value]"
                                                    placeholder="Enter value" value="{{ $value }}">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex gap-1">
                                                    <div class="form-check form-switch pt-1">
                                                        <input class="form-check-input filterable-check" type="checkbox"
                                                            name="spec_categories[{{ $categoryIndex }}][fields][{{ $fieldIndex }}][filterable]"
                                                            value="1" {{ $field->is_filterable ? 'checked' : '' }}>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-field">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-secondary add-field mt-2"
                                    data-category="{{ $category->slug }}">
                                    <i class="bi bi-plus me-1"></i> Add Field
                                </button>
                            </div>
                            @php $categoryIndex++; @endphp
                        @endforeach
                    @else
                        <!-- Show default categories for new device -->
                        @foreach($defaultCategories as $categoryName => $fields)
                            <div class="category-section mb-4 p-3 border rounded"
                                data-category="{{ \Illuminate\Support\Str::slug($categoryName) }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="category-title mb-0 text-dark">
                                        <input type="text" class="form-control category-name"
                                            name="spec_categories[{{ $loop->index }}][name]" value="{{ $categoryName }}"
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
                                                <input type="text" class="form-control field-label"
                                                    name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][label]"
                                                    value="{{ $fieldLabel }}" placeholder="Field Label">
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
                                                <input type="text" class="form-control field-value"
                                                    name="spec_categories[{{ $loop->parent->index }}][fields][{{ $fieldIndex }}][value]"
                                                    placeholder="Enter value"
                                                    value="{{ old("spec_categories.{$loop->parent->index}.fields.{$fieldIndex}.value") }}">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex gap-1">
                                                    <div class="form-check form-switch pt-1">
                                                        <input class="form-check-input filterable-check" type="checkbox"
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

                                <button type="button" class="btn btn-sm btn-outline-secondary add-field mt-2"
                                    data-category="{{ \Illuminate\Support\Str::slug($categoryName) }}">
                                    <i class="bi bi-plus me-1"></i> Add Field
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Templates remain the same -->
                <template id="categoryTemplate">
                    <div class="category-section mb-4 p-3 border rounded" data-category="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="category-title mb-0 text-dark">
                                <input type="text" class="form-control category-name" name="" value=""
                                    placeholder="Category Name">
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
                            <input type="text" class="form-control field-label" name="" value=""
                                placeholder="Field Label">
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
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success" id="activateAllVariants">
                            <i class="bi bi-check-circle me-1"></i> Active All
                        </button>
                        <button type="button" class="btn btn-outline-danger" id="deactivateAllVariants">
                            <i class="bi bi-x-circle me-1"></i> Inactive All
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addVariant">
                        <i class="bi bi-plus-circle me-1"></i> Add Variant
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="variantsContainer">
                    @if($isEdit && $device->allVariants->count() > 0)
                        <!-- Show existing variants for editing -->
                        @foreach($device->allVariants as $variantIndex => $variant)
                            <div class="variant-section mb-4 p-3 border rounded" data-variant-index="{{ $variantIndex }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="badge {{ $variant->is_primary ? 'bg-primary' : 'bg-secondary' }} me-2 variant-badge">
                                            {{ $variant->is_primary ? 'Primary' : 'Variant' }}
                                        </span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input primary-variant" type="radio" name="primary_variant"
                                                value="{{ $variantIndex }}" {{ $variant->is_primary ? 'checked' : '' }}>
                                            <label class="form-check-label">Set as Primary</label>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Variant Label</label>
                                        <input type="text" class="form-control variant-label"
                                            name="variants[{{ $variantIndex }}][label]" value="{{ $variant->label }}"
                                            placeholder="e.g., 128GB 8GB RAM">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">RAM (GB)</label>
                                        <input type="number" class="form-control" name="variants[{{ $variantIndex }}][ram_gb]"
                                            value="{{ $variant->ram_gb }}" placeholder="e.g., 8">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Storage (GB)</label>
                                        <input type="number" class="form-control"
                                            name="variants[{{ $variantIndex }}][storage_gb]" value="{{ $variant->storage_gb }}"
                                            placeholder="e.g., 128">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Model Code</label>
                                        <input type="text" class="form-control" name="variants[{{ $variantIndex }}][model_code]"
                                            value="{{ $variant->model_code }}" placeholder="e.g., SM-XXXX">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox"
                                                name="variants[{{ $variantIndex }}][is_active]" value="1" {{ $variant->status ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Offers Section -->
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Pricing & Offers</h6>
                                        <button type="button" class="btn btn-sm btn-outline-secondary add-offer"
                                            data-variant-index="{{ $variantIndex }}">
                                            <i class="bi bi-plus me-1"></i> Add Offer
                                        </button>
                                    </div>
                                    <div class="offers-container" data-variant-index="{{ $variantIndex }}">
                                        @foreach($variant->offers as $offerIndex => $offer)
                                            <div class="offer-item border p-3 mb-3 rounded">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Store</label>
                                                        <select class="form-select store-select"
                                                            name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][store_id]">
                                                            <option value="">Select Store</option>
                                                            @foreach($stores as $store)
                                                                <option value="{{ $store->id }}" {{ $offer->store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Country</label>
                                                        <select class="form-select country-select"
                                                            name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][country_id]">
                                                            <option value="">Select Country</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}" {{ $offer->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Currency</label>
                                                        <select class="form-select currency-select"
                                                            name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][currency_id]">
                                                            <option value="">Select Currency</option>
                                                            @foreach($currencies as $currency)
                                                                <option value="{{ $currency->id }}" {{ $offer->currency_id == $currency->id ? 'selected' : '' }}>
                                                                    {{ $currency->iso_code }} ({{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Price</label>
                                                        <input type="number" step="0.01" class="form-control price-input"
                                                            name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][price]"
                                                            value="{{ $offer->price }}" placeholder="0.00">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Product URL</label>
                                                        <input type="url" class="form-control"
                                                            name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][url]"
                                                            value="{{ $offer->url }}" placeholder="https://...">
                                                    </div>

                                                    <!-- Active/Inactive Radio Buttons -->
                                                    <div class="col-md-4">
                                                        <label class="form-label">Offer Status</label>
                                                        <div class="d-flex gap-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input offer-status" type="radio"
                                                                    name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][status]"
                                                                    value="1"
                                                                    id="offer_active_{{ $variantIndex }}_{{ $offerIndex }}" {{ $offer->status ? 'checked' : '' }}>
                                                                <label class="form-check-label text-success"
                                                                    for="offer_active_{{ $variantIndex }}_{{ $offerIndex }}">
                                                                    <i class="bi bi-check-circle me-1"></i> Active
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input offer-status" type="radio"
                                                                    name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][status]"
                                                                    value="0"
                                                                    id="offer_inactive_{{ $variantIndex }}_{{ $offerIndex }}" {{ !$offer->status ? 'checked' : '' }}>
                                                                <label class="form-check-label text-danger"
                                                                    for="offer_inactive_{{ $variantIndex }}_{{ $offerIndex }}">
                                                                    <i class="bi bi-x-circle me-1"></i> Inactive
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][in_stock]"
                                                                value="1" {{ $offer->in_stock ? 'checked' : '' }}>
                                                            <label class="form-check-label">In Stock</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="variants[{{ $variantIndex }}][offers][{{ $offerIndex }}][is_featured]"
                                                                value="1" {{ $offer->is_featured ? 'checked' : '' }}>
                                                            <label class="form-check-label">Featured Offer</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger remove-offer mt-4">
                                                            <i class="bi bi-trash me-1"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Show empty variant for new device -->
                        <div class="text-center py-4 text-muted" id="noVariantsMessage">
                            <i class="bi bi-layers display-4"></i>
                            <p class="mt-2 mb-0">No variants added yet</p>
                            <small>Click "Add Variant" to create device variants</small>
                        </div>
                    @endif
                </div>

                <template id="variantTemplate">
                    <div class="variant-section mb-4 p-3 border rounded" data-variant-index="">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2 variant-badge">Variant</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input primary-variant" type="radio" name="primary_variant"
                                        value="">
                                    <label class="form-check-label">Set as Primary</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Variant Label</label>
                                <input type="text" class="form-control variant-label" name="variants[][label]"
                                    placeholder="e.g., 128GB 8GB RAM">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">RAM (GB)</label>
                                <input type="number" class="form-control" name="variants[][ram_gb]"
                                    placeholder="e.g., 8">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Storage (GB)</label>
                                <input type="number" class="form-control" name="variants[][storage_gb]"
                                    placeholder="e.g., 128">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model Code</label>
                                <input type="text" class="form-control" name="variants[][model_code]"
                                    placeholder="e.g., SM-XXXX">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="variants[][is_active]"
                                        value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>

                        <!-- Offers Section -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Pricing & Offers</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary add-offer"
                                    data-variant-index="">
                                    <i class="bi bi-plus me-1"></i> Add Offer
                                </button>
                            </div>
                            <div class="offers-container">
                                <!-- Offers will be added here -->
                            </div>
                        </div>
                    </div>
                </template>

                <template id="offerTemplate">
                    <div class="offer-item border p-3 mb-3 rounded">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Store</label>
                                <select class="form-select store-select" name="variants[][offers][][store_id]">
                                    <option value="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Country</label>
                                <select class="form-select country-select" name="variants[][offers][][country_id]">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Currency</label>
                                <select class="form-select currency-select" name="variants[][offers][][currency_id]">
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->iso_code }}
                                            ({{ $currency->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control price-input"
                                    name="variants[][offers][][price]" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Product URL</label>
                                <input type="url" class="form-control" name="variants[][offers][][url]"
                                    placeholder="https://...">
                            </div>

                            <!-- Active/Inactive Radio Buttons -->
                            <div class="col-md-4">
                                <label class="form-label">Offer Status</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input offer-status" type="radio"
                                            name="variants[][offers][][status]" value="1" id="offer_active_" checked>
                                        <label class="form-check-label text-success" for="offer_active_">
                                            <i class="bi bi-check-circle me-1"></i> Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input offer-status" type="radio"
                                            name="variants[][offers][][status]" value="0" id="offer_inactive_">
                                        <label class="form-check-label text-danger" for="offer_inactive_">
                                            <i class="bi bi-x-circle me-1"></i> Inactive
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        name="variants[][offers][][in_stock]" value="1" checked>
                                    <label class="form-check-label">In Stock</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        name="variants[][offers][][is_featured]" value="1">
                                    <label class="form-check-label">Featured Offer</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-offer mt-4">
                                    <i class="bi bi-trash me-1"></i> Remove
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
                    @if($isEdit && $device->videos->count() > 0)
                        <!-- Show existing videos for editing -->
                        @foreach($device->videos as $videoIndex => $video)
                            <div class="video-item card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Video Title</label>
                                                <input type="text" class="form-control video-title"
                                                    name="videos[{{ $videoIndex }}][title]" value="{{ $video->title }}"
                                                    placeholder="Enter video title" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">YouTube URL or ID</label>
                                                <input type="text" class="form-control youtube-url"
                                                    name="videos[{{ $videoIndex }}][youtube_id]"
                                                    value="{{ $video->youtube_id }}"
                                                    placeholder="e.g., https://youtube.com/watch?v=ABC123 or ABC123" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control video-description"
                                                    name="videos[{{ $videoIndex }}][description]" rows="2"
                                                    placeholder="Optional video description">{{ $video->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Video Preview</label>
                                                <div class="video-preview embed-responsive embed-responsive-16by9 bg-light rounded"
                                                    style="min-height: 300px; position: relative; width: 100%;">
                                                    @php
                                                        $videoId = extractYouTubeId($video->youtube_id);
                                                    @endphp
                                                    @if($videoId)
                                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                            class="embed-responsive-item" frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen
                                                            style="position:absolute; width: 100%; height: 100%; border-radius: 0.375rem;">
                                                        </iframe>
                                                    @else
                                                        <div
                                                            class="embed-responsive-item d-flex align-items-center justify-content-center text-muted position-absolute top-0 left-0 h-100 w-100">
                                                            <div class="text-center">
                                                                <i class="bi bi-play-circle display-4"></i>
                                                                <p class="mt-2 mb-0">Preview will appear here</p>
                                                            </div>
                                                        </div>
                                                    @endif
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
                                                    <input class="form-check-input" type="checkbox"
                                                        name="videos[{{ $videoIndex }}][is_published]" value="1" {{ $video->is_published ? 'checked' : '' }}>
                                                    <label class="form-check-label">Publish this video</label>
                                                </div>
                                                <small class="text-muted d-block mt-2">Enter YouTube URL and click "Preview" to
                                                    see the video</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Show empty state for new device -->
                        <div class="text-center py-4 text-muted" id="noVideosMessage">
                            <i class="bi bi-play-circle display-4"></i>
                            <p class="mt-2 mb-0">No videos added yet</p>
                            <small>Click "Add Video" to add YouTube links or upload videos</small>
                        </div>
                    @endif
                </div>

                <!-- Video Template for new videos -->
                <template id="videoTemplate">
                    <div class="video-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Title</label>
                                        <input type="text" class="form-control video-title" name="videos[][title]"
                                            placeholder="Enter video title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">YouTube URL or ID</label>
                                        <input type="text" class="form-control youtube-url" name="videos[][youtube_id]"
                                            placeholder="e.g., https://youtube.com/watch?v=ABC123 or ABC123" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control video-description" name="videos[][description]"
                                            rows="2" placeholder="Optional video description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Preview</label>
                                        <div class="video-preview embed-responsive embed-responsive-16by9 bg-light rounded"
                                            style="min-height: 300px; position: relative; width: 100%;">
                                            <div
                                                class="embed-responsive-item d-flex align-items-center justify-content-center text-muted position-absolute top-0 left-0 h-100 w-100">
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
                                            <input class="form-check-input" type="checkbox"
                                                name="videos[][is_published]" value="1" checked>
                                            <label class="form-check-label">Publish this video</label>
                                        </div>
                                        <small class="text-muted d-block mt-2">Enter YouTube URL and click "Preview" to
                                            see the video</small>
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
                    @if($isEdit && $device->imageGroups->count() > 0)
                        <!-- Show existing image groups for editing -->
                        @foreach($device->imageGroups as $groupIndex => $group)
                            <div class="image-group-section mb-4 p-3 border rounded" data-group-index="{{ $groupIndex }}">
                                <input type="hidden" name="image_groups[{{ $groupIndex }}][id]" value="{{ $group->id }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="group-title mb-0 text-dark">
                                        <span class="badge bg-primary me-2">{{ $group->title }}</span>
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-group">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Category Title</label>
                                            <input type="text" class="form-control group-title-input"
                                                name="image_groups[{{ $groupIndex }}][title]" value="{{ $group->title }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Category Type</label>
                                            <input type="text" class="form-control group-type-input"
                                                name="image_groups[{{ $groupIndex }}][group_type]"
                                                value="{{ $group->group_type }}" placeholder="e.g., official, leaks, renders"
                                                required>
                                            <small class="text-muted">Enter any category type you want</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Display Order</label>
                                            <input type="number" class="form-control"
                                                name="image_groups[{{ $groupIndex }}][order]" value="{{ $group->order }}"
                                                min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Images in this group -->
                                <div class="images-section mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-muted mb-0">Images in this category</h6>
                                        <button type="button" class="btn btn-sm btn-outline-secondary add-image"
                                            data-group-index="{{ $groupIndex }}">
                                            <i class="bi bi-plus me-1"></i> Add Image
                                        </button>
                                    </div>

                                    <div class="images-container" data-group-index="{{ $groupIndex }}">
                                        @if($group->images->count() > 0)
                                            @foreach($group->images as $imageIndex => $image)
                                                <div class="image-item card mb-3">
                                                    <input type="hidden"
                                                        name="image_groups[{{ $groupIndex }}][images][{{ $imageIndex }}][id]"
                                                        value="{{ $image->id }}">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label class="form-label">Image</label>
                                                                    <div class="image-upload-container">
                                                                        <!-- Show existing image -->
                                                                        <div class="existing-image-preview">
                                                                            <img src="{{ asset('storage/' . $image->image_url) }}"
                                                                                alt="{{ $image->caption }}"
                                                                                class="img-thumbnail rounded"
                                                                                style="max-width: 200px; max-height: 200px; object-fit: contain;">
                                                                            <input type="hidden"
                                                                                name="image_groups[{{ $groupIndex }}][images][{{ $imageIndex }}][existing_image]"
                                                                                value="{{ $image->image_url }}">
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <small class="text-muted">Current image. Upload new one to
                                                                                replace.</small>
                                                                        </div>
                                                                        <!-- Upload new image -->
                                                                        <div class="upload-area mt-2"
                                                                            id="group_{{ $groupIndex }}_image_{{ $imageIndex }}_upload"
                                                                            style="display:none;">
                                                                            <div class="upload-placeholder">
                                                                                <i class="bi bi-image display-4 text-muted"></i>
                                                                                <p class="mt-2 mb-1 text-muted">Click to upload new
                                                                                    image</p>
                                                                                <small class="text-muted">PNG, JPG up to 2MB</small>
                                                                            </div>
                                                                            <input type="file" class="form-control d-none"
                                                                                id="group_{{ $groupIndex }}_image_{{ $imageIndex }}"
                                                                                name="image_groups[{{ $groupIndex }}][images][{{ $imageIndex }}][image]"
                                                                                accept="image/*">
                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-secondary mt-2"
                                                                            onclick="toggleImageUpload('group_{{ $groupIndex }}_image_{{ $imageIndex }}')">
                                                                            <i class="bi bi-arrow-repeat me-1"></i> Replace Image
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">Caption</label>
                                                                    <input type="text" class="form-control image-caption"
                                                                        name="image_groups[{{ $groupIndex }}][images][{{ $imageIndex }}][caption]"
                                                                        value="{{ $image->caption }}"
                                                                        placeholder="Optional image caption">
                                                                    <label class="form-label mt-2">Display Order</label>
                                                                    <input type="number" class="form-control image-order"
                                                                        name="image_groups[{{ $groupIndex }}][images][{{ $imageIndex }}][order]"
                                                                        value="{{ $image->order }}" min="0">
                                                                    <div class="mt-3">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-danger w-100 remove-image">
                                                                            <i class="bi bi-trash me-1"></i> Remove Image
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3 text-muted">
                                                <i class="bi bi-image display-6"></i>
                                                <p class="mt-2 mb-0">No images added yet</p>
                                                <small>Click "Add Image" to upload photos</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Show default group for new device -->
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
                                        <input type="text" class="form-control group-title-input"
                                            name="image_groups[0][title]" value="Official Images" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Category Type</label>
                                        <input type="text" class="form-control group-type-input"
                                            name="image_groups[0][group_type]" value="official"
                                            placeholder="e.g., official, leaks, renders" required>
                                        <small class="text-muted">Enter any category type you want</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control" name="image_groups[0][order]" value="0"
                                            min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Images in this group -->
                            <div class="images-section mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-muted mb-0">Images in this category</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary add-image"
                                        data-group-index="0">
                                        <i class="bi bi-plus me-1"></i> Add Image
                                    </button>
                                </div>

                                <div class="images-container" data-group-index="0">
                                    <div class="text-center py-3 text-muted">
                                        <i class="bi bi-image display-6"></i>
                                        <p class="mt-2 mb-0">No images added yet</p>
                                        <small>Click "Add Image" to upload photos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Templates for new groups/images -->
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
                                    <input type="text" class="form-control group-title-input"
                                        name="image_groups[][title]" value=""
                                        placeholder="e.g., Our Photos, Leaks, Renders" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Category Type</label>
                                    <input type="text" class="form-control group-type-input"
                                        name="image_groups[][group_type]" value=""
                                        placeholder="e.g., official, leaks, renders, unboxing" required>
                                    <small class="text-muted">Enter any category type you want</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="image_groups[][order]" value="0"
                                        min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Images in this group -->
                        <div class="images-section mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-muted mb-0">Images in this category</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary add-image"
                                    data-group-index="">
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
                                        <input type="text" class="form-control image-caption"
                                            name="image_groups[][images][][caption]"
                                            placeholder="Optional image caption">
                                        <label class="form-label mt-2">Display Order</label>
                                        <input type="number" class="form-control image-order"
                                            name="image_groups[][images][][order]" value="0" min="0">
                                        <div class="mt-3">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger w-100 remove-image">
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


<!-- Section 7: Device Reviews -->
{{--
<div class="row mb-4">
    <div class="col-12">
        <div class="card">

            <div class="card-header bg-light-info d-flex justify-content-between align-items-center">
                <h5 class="card-title text-info mb-0">
                    <i class="bi bi-star me-2"></i> Device Review
                </h5>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="review[is_published]" value="1"
                        id="reviewPublished" {{ $existingReview && $existingReview->is_published ? 'checked' : '' }}>
                    <label class="form-check-label" for="reviewPublished">Publish Review</label>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4">

                    <div class="col-md-8">
                        <label class="form-label">Review Title *</label>
                        <input type="text" class="form-control" name="review[title]"
                            value="{{ old('review.title', $existingReview->title ?? '') }}"
                            placeholder="e.g., Apple iPhone 17 Pro Max Review" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Review Slug *</label>
                        <input type="text" class="form-control" name="review[slug]"
                            value="{{ old('review.slug', $existingReview->slug ?? '') }}"
                            placeholder="e.g., apple-iphone-17-pro-max-review" required>
                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-md-6">
                        <label class="form-label">Cover Image</label>
                        <x-image-upload fieldName="review[cover_image]"
                            :existingImage="$existingReview->cover_image_url ?? null" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Publish Date</label>
                        <input type="datetime-local" class="form-control" name="review[published_at]"
                            value="{{ $existingReview && $existingReview->published_at ? $existingReview->published_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                </div>

                <div class="border-top pt-3">
                    <div class="mb-3">
                        <label class="form-label">Review Content *</label>
                        <textarea class="form-control review-content" name="review[body]" rows="6"
                            placeholder="Write detailed review content here...">{{ old('review.body', $existingReview->body ?? '') }}</textarea>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
--}}

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
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                value="1" {{ old('is_published', $device->is_published ?? 0) ? 'checked' : '' }}>
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
                            <input class="form-check-input" type="checkbox" id="allow_opinions" name="allow_opinions"
                                value="1" {{ old('allow_opinions', $device->allow_opinions ?? 1) ? 'checked' : '' }}>
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
                            <input class="form-check-input" type="checkbox" id="allow_fans" name="allow_fans" value="1"
                                {{ old('allow_fans', $device->allow_fans ?? 1) ? 'checked' : '' }}>
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




@push('scripts')
    <!-- jQuery + Summernote Js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <!-- Image preview in basic information section -->
    <script>
        document.getElementById('thumbnailInput').addEventListener('change', function (e) {
            let file = e.target.files[0];

            if (!file) return;

            let reader = new FileReader();

            reader.onload = function (evt) {
                let preview = document.getElementById('thumbnailPreview');
                preview.src = evt.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        });
    </script>


    <!-- Device Specifications -->
    <script>
        document.getElementById('color_picker').addEventListener('input', function () {
            document.getElementById('color_short').value = this.value;
        });


        document.addEventListener('DOMContentLoaded', function () {
            @if($isEdit && $device->specValues->count() > 0)
                let categoryCounter = {{ count($categories ?? []) }};
            @else
                let categoryCounter = {{ count($defaultCategories) }};
            @endif

            let fieldCounters = {};

            // Initialize field counters for existing categories
            document.querySelectorAll('.category-section').forEach(category => {
                const categorySlug = category.dataset.category;
                fieldCounters[categorySlug] = category.querySelectorAll('.field-group').length;

                // Set data-category attribute on add field button
                const addFieldBtn = category.querySelector('.add-field');
                if (addFieldBtn) {
                    addFieldBtn.setAttribute('data-category', categorySlug);
                }
            });
            // Add new category
            document.getElementById('addSpecCategory').addEventListener('click', function () {
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
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-field') || e.target.closest('.add-field')) {
                    const addFieldBtn = e.target.classList.contains('add-field') ? e.target : e.target.closest('.add-field');
                    const categorySlug = addFieldBtn.dataset.category || addFieldBtn.closest('.category-section').dataset.category;
                    addFieldToCategory(categorySlug);
                }
            });

            // Remove category with confirmation
            document.addEventListener('click', function (e) {
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
            const deviceForm = document.getElementById('deviceForm');
            if (deviceForm) {
                deviceForm.addEventListener('submit', function (e) {
                    const hasSpecifications = document.querySelectorAll('.field-group').length > 0;
                    if (!hasSpecifications) {
                        if (!confirm('No specifications added. Continue without specifications?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    return true;
                });
            }
        });
    </script>


    <!-- Device Variants & Pricing -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($isEdit && $device->allVariants->count() > 0)
                let variantCounter = {{ $device->allVariants->count() }};
                // Initialize offer counters for existing variants
                let offerCounters = {
                    @foreach($device->allVariants as $variantIndex => $variant)
                        {{ $variantIndex }}: {{ $variant->offers->count() }},
                    @endforeach
                                                                                };
            @else
            let variantCounter = 0;
            let offerCounters = {};
            // Add first variant automatically for new device -> Disabled to default to empty
            // addNewVariant();
        @endif

        // Add new variant
        document.getElementById('addVariant').addEventListener('click', function () {
            addNewVariant();
        });

        function addNewVariant() {
            const template = document.getElementById('variantTemplate');
            const clone = template.content.cloneNode(true);
            const variantSection = clone.querySelector('.variant-section');

            variantSection.dataset.variantIndex = variantCounter;
            offerCounters[variantCounter] = 0;

            // Update all inputs and selects
            updateVariantInputs(variantSection, variantCounter);

            // Set first variant as primary if it's the first one
            if (variantCounter === 0) {
                const primaryRadio = variantSection.querySelector('.primary-variant');
                primaryRadio.checked = true;
            }

            // Hide no variants message
            const noVariantsMessage = document.getElementById('noVariantsMessage');
            if (noVariantsMessage) {
                noVariantsMessage.style.display = 'none';
            }

            document.getElementById('variantsContainer').appendChild(variantSection);
            variantCounter++;
        }

        // Remove variant
        document.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-variant');
            if (removeBtn) {
                if (!confirm('Are you sure you want to remove this variant and all its offers?')) {
                    return;
                }
                const variantSection = removeBtn.closest('.variant-section');
                const variantIndex = parseInt(variantSection.dataset.variantIndex);
                delete offerCounters[variantIndex];
                variantSection.remove();

                // Show no variants message if no variants left
                const variants = document.querySelectorAll('.variant-section');
                if (variants.length === 0) {
                    const noVariantsMessage = document.getElementById('noVariantsMessage');
                    if (noVariantsMessage) {
                        noVariantsMessage.style.display = 'block';
                    }
                }

                // If we removed the primary variant, set the first remaining variant as primary
                updatePrimaryVariant();
            }
        });

        // Handle primary variant selection
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('primary-variant') && e.target.checked) {
                updatePrimaryVariantBadges();
            }
        });

        // Add offer to variant
        document.addEventListener('click', function (e) {
            const addOfferBtn = e.target.closest('.add-offer');
            if (addOfferBtn) {
                const variantIndex = parseInt(addOfferBtn.dataset.variantIndex);
                addOfferToVariant(variantIndex);
            }
        });

        // Remove offer
        document.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-offer');
            if (removeBtn) {
                const offerItem = removeBtn.closest('.offer-item');
                offerItem.remove();
            }
        });

        function updateVariantInputs(variantSection, variantIndex) {
            // Update variant main inputs
            const mainInputs = variantSection.querySelectorAll('input:not(.primary-variant), select');
            mainInputs.forEach(input => {
                const currentName = input.name;
                if (currentName.startsWith('variants[]')) {
                    input.name = currentName.replace('variants[]', `variants[${variantIndex}]`);
                }
            });

            // Update primary variant radio
            const primaryRadio = variantSection.querySelector('.primary-variant');
            primaryRadio.name = 'primary_variant';
            primaryRadio.value = variantIndex;

            // Update add offer button
            const addOfferBtn = variantSection.querySelector('.add-offer');
            addOfferBtn.dataset.variantIndex = variantIndex;

            // Update offers container
            const offersContainer = variantSection.querySelector('.offers-container');
            offersContainer.dataset.variantIndex = variantIndex;

            // Update variant badge
            updatePrimaryVariantBadges();
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

            // Update all inputs and selects
            offerItem.querySelector('.store-select').name = `${baseName}[store_id]`;
            offerItem.querySelector('.country-select').name = `${baseName}[country_id]`;
            offerItem.querySelector('.currency-select').name = `${baseName}[currency_id]`;
            offerItem.querySelector('.price-input').name = `${baseName}[price]`;

            const urlInput = offerItem.querySelector('input[type="url"]');
            urlInput.name = `${baseName}[url]`;

            // Update checkboxes
            const checkboxes = offerItem.querySelectorAll('input[type="checkbox"]');
            checkboxes[0].name = `${baseName}[in_stock]`;
            checkboxes[1].name = `${baseName}[is_featured]`;

            // Update radio buttons for status with unique IDs
            const radioButtons = offerItem.querySelectorAll('.offer-status');
            const activeRadio = radioButtons[0];
            const inactiveRadio = radioButtons[1];

            activeRadio.name = `${baseName}[status]`;
            inactiveRadio.name = `${baseName}[status]`;

            // Set unique IDs for labels
            const activeId = `offer_active_${variantIndex}_${offerIndex}`;
            const inactiveId = `offer_inactive_${variantIndex}_${offerIndex}`;

            activeRadio.id = activeId;
            inactiveRadio.id = inactiveId;

            // Update label for attributes
            activeRadio.nextElementSibling.setAttribute('for', activeId);
            inactiveRadio.nextElementSibling.setAttribute('for', inactiveId);

            offersContainer.appendChild(offerItem);
        }
                            });

        // Bulk toggle variants
        document.getElementById('activateAllVariants').addEventListener('click', function () {
            document.querySelectorAll('#variantsContainer input[name*="[is_active]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        });

        document.getElementById('deactivateAllVariants').addEventListener('click', function () {
            document.querySelectorAll('#variantsContainer input[name*="[is_active]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    </script>

    <!-- Device videos -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize counter based on whether we're editing or creating
            @if($isEdit && $device->videos->count() > 0)
                let videoCounter = {{ $device->videos->count() }};
            @else
                let videoCounter = 0;
            @endif

            // Add new video
            document.getElementById('addVideo').addEventListener('click', function () {
                addNewVideo();
            });

            function addNewVideo() {
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
                const noVideosMessage = document.getElementById('noVideosMessage');
                if (noVideosMessage) {
                    noVideosMessage.style.display = 'none';
                }

                videoCounter++;
            }

            // Remove video
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-video') || e.target.closest('.remove-video')) {
                    const removeBtn = e.target.classList.contains('remove-video') ? e.target : e.target.closest('.remove-video');
                    const videoItem = removeBtn.closest('.video-item');

                    if (confirm('Are you sure you want to remove this video?')) {
                        videoItem.remove();

                        // Show no videos message if no videos left
                        const videoItems = document.querySelectorAll('.video-item');
                        if (videoItems.length === 0) {
                            const noVideosMessage = document.getElementById('noVideosMessage');
                            if (noVideosMessage) {
                                noVideosMessage.style.display = 'block';
                            }
                        }
                    }
                }
            });

            // Preview YouTube video
            document.addEventListener('click', function (e) {
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
                                                        class="embed-responsive-item" 
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen
                                                        style="position:absolute; width: 100%; height: 100%; border-radius: 0.375rem;">
                                                    </iframe>
                                                `;
                }
            });

            // Auto-preview when YouTube URL is pasted
            document.addEventListener('input', function (e) {
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
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize counters based on whether we're editing or creating
            @if($isEdit && $device->imageGroups->count() > 0)
                let groupCounter = {{ $device->imageGroups->count() }};
                let imageCounters = {
                    @foreach($device->imageGroups as $groupIndex => $group)
                        {{ $groupIndex }}: {{ $group->images->count() }},
                    @endforeach
                                                                                };
            @else
            let groupCounter = 1;
            let imageCounters = { 0: 0 };
        @endif

        // Add new image group
        document.getElementById('addImageGroup').addEventListener('click', function () {
            addNewImageGroup();
        });

        function addNewImageGroup() {
            const template = document.getElementById('imageGroupTemplate');
            const clone = template.content.cloneNode(true);
            const groupSection = clone.querySelector('.image-group-section');

            groupSection.dataset.groupIndex = groupCounter;
            imageCounters[groupCounter] = 0;

            updateGroupInputs(groupSection, groupCounter);
            document.getElementById('imageGroupsContainer').appendChild(groupSection);
            groupCounter++;
        }

        // Remove image group
        document.addEventListener('click', function (e) {
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
        document.addEventListener('click', function (e) {
            if (e.target.closest('.add-image')) {
                const addBtn = e.target.closest('.add-image');
                const groupIndex = addBtn.dataset.groupIndex;
                addImageToGroup(groupIndex);
            }
        });

        // Remove image
        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-image')) {
                const removeBtn = e.target.closest('.remove-image');
                const imageItem = removeBtn.closest('.image-item');

                // mark image as removed
                const groupIndex = imageItem.closest('.image-group-section').dataset.groupIndex;
                const imageIndex = Array.from(imageItem.parentNode.children).indexOf(imageItem);

                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = `image_groups[${groupIndex}][images][${imageIndex}][remove]`;
                removeInput.value = "1";

                imageItem.appendChild(removeInput);

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

        // Function to toggle image upload for existing images
        function toggleImageUpload(fieldName) {
            const uploadArea = document.getElementById(fieldName + '_upload');
            const existingPreview = uploadArea.previousElementSibling.previousElementSibling;

            if (uploadArea.style.display === 'none') {
                uploadArea.style.display = 'block';
                existingPreview.style.display = 'none';
            } else {
                uploadArea.style.display = 'none';
                existingPreview.style.display = 'block';
            }
        }
    </script>

    <!-- Device Reviews Section Management -->
    <script>
        $(document).ready(function () {
            $('.review-content').summernote({
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
                callbacks: {
                    onPaste: function (e) {
                        const bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                }
            });
        });

        document.getElementById('reviewCoverInput').addEventListener('change', function (e) {
            let file = e.target.files[0];
            if (!file) return;

            let reader = new FileReader();
            reader.onload = function (evt) {
                let preview = document.getElementById('reviewCoverPreview');
                preview.src = evt.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    </script>

    <!-- Device SEO -->
    <script src="{{ asset('admin/assets/js/seo-management.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            let isAutoSlug = {{ $isEdit ? 'false' : 'true' }};

            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    if (isAutoSlug) {
                        slugInput.value = slugify(this.value);
                    }
                });

                slugInput.addEventListener('input', function() {
                    isAutoSlug = false;
                    if (this.value.trim() === '') {
                        isAutoSlug = true;
                        slugInput.value = slugify(nameInput.value);
                    }
                });
            }

            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')              // Trim - from start of text
                    .replace(/-+$/, '');             // Trim - from end of text
            }
        });
    </script>
@endpush
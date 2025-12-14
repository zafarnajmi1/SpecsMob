@php
    /** @var \App\Models\Review|null $review */
    $isEdit = isset($review);
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

<!-- Section 1: Review Reviews -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-info d-flex justify-content-between align-items-center">
                <h5 class="card-title text-info mb-0">
                    <i class="bi bi-star me-2"></i>Review
                </h5>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="review[is_published]" value="1"
                        id="reviewPublished" {{ ($isEdit && $review->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="reviewPublished">Publish Review</label>
                </div>
            </div>
            <div class="card-body">
                <!-- Main Review Information -->
                 <div class="row mb-4">
    {{-- Brand --}}
    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Brand <span class="text-danger">*</span></label>
            @php
                $existingReview = $isEdit ? $review : null;
                $selectedBrandId = old('review.brand_id', $existingReview->brand_id ?? null);
            @endphp

            <select class="form-select brand-select" name="review[brand_id]" required>
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                        {{ (int) $selectedBrandId === (int) $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Device --}}
    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Device <span class="text-danger">*</span></label>

            @php
                $selectedDeviceId = old('review.device_id', $existingReview->device_id ?? null);
                $selectedBrand = $brands->firstWhere('id', $selectedBrandId);
            @endphp

            <select class="form-select device-select" name="review[device_id]" required {{ $selectedBrand ? '' : 'disabled' }}>
                <option value="">Select Device</option>

                @if($selectedBrand)
                    @foreach($selectedBrand->devices as $device)
                        <option value="{{ $device->id }}"
                            {{ (int) $selectedDeviceId === (int) $device->id ? 'selected' : '' }}>
                            {{ $device->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Review Title *</label>
                            <input type="text" class="form-control" name="review[title]"
                                value="{{ $isEdit ? $review->title : '' }}"
                                placeholder="e.g., Apple iPhone 17 Pro Max Review" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Review Slug *</label>
                            <input type="text" class="form-control" name="review[slug]"
                                value="{{ $isEdit > 0 ? $review->slug : '' }}"
                                placeholder="e.g., apple-iphone-17-pro-max-review" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <x-image-upload fieldName="cover_image" :existingImage="$isEdit > 0 ? $review->cover_image_url : null" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Publish Date</label>
                            <input type="datetime-local" class="form-control" name="review[published_at]"
                                value="{{ $isEdit > 0 && $review->published_at ? $review->published_at->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                </div>

                <!-- Review Sections -->
                <div class="border-top pt-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Review Content *</label>
                                <textarea class="form-control review-content" name="review[body]"
                                    placeholder="Write detailed review content here...">{{ old('review.body', $review->body ?? '') }}</textarea>
                            </div>
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

    <!-- Review Reviews Section Management -->
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
    </script>

    <!-- Brand selection show devices -->
     <script>
document.addEventListener('DOMContentLoaded', function () {
    const brandSelect  = document.querySelector('.brand-select');
    const deviceSelect = document.querySelector('.device-select');

    if (!brandSelect || !deviceSelect) return;

    brandSelect.addEventListener('change', function () {
        const brandId = this.value;

        // Reset device dropdown
        deviceSelect.innerHTML = '<option value="">Select Device</option>';

        if (!brandId) {
            deviceSelect.disabled = true;
            return;
        }

        deviceSelect.disabled = true;
        deviceSelect.innerHTML = '<option value="">Loading devices...</option>';

        fetch(`/admin/brands/${brandId}/devices`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.json();
            })
            .then(devices => {
                deviceSelect.disabled = false;
                deviceSelect.innerHTML = '<option value="">Select Device</option>';

                if (devices.length === 0) {
                    deviceSelect.innerHTML = '<option value="">No devices found</option>';
                    return;
                }

                devices.forEach(device => {
                    const option = document.createElement('option');
                    option.value = device.id;
                    option.textContent = device.name;
                    deviceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching devices:', error);
                deviceSelect.disabled = false;
                deviceSelect.innerHTML = '<option value="">Error loading devices</option>';
            });
    });
});
</script>
@endpush
@php
    /** @var \App\Models\Review|null $review */
    $isEdit = isset($review);
@endphp

@push('styles')
    <style>
        .card-header.bg-light-primary {
            background-color: rgba(67, 94, 190, 0.1) !important;
            border-bottom: 1px solid rgba(67, 94, 190, 0.2);
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
    </style>
@endpush

<!-- Section 1: Review Details -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-none border">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-star me-2"></i>Review Content & Details
                </h5>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="review[is_published]" value="1"
                        id="reviewPublished" {{ ($isEdit && $review->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="reviewPublished">Publish Review</label>
                </div>
            </div>
            <div class="card-body pt-4">
                <!-- Brand & Device Selection -->
                <div class="row mb-4">
                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                            @php
                                $selectedBrandId = old('review.brand_id', $isEdit ? $review->brand_id : null);
                            @endphp
                            <select class="form-select brand-select" name="review[brand_id]" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ (int) $selectedBrandId === (int) $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Device <span class="text-danger">*</span></label>
                            @php
                                $selectedDeviceId = old('review.device_id', $isEdit ? $review->device_id : null);
                                $selectedBrand = $brands->firstWhere('id', $selectedBrandId);
                            @endphp
                            <select class="form-select device-select" name="review[device_id]" required {{ $selectedBrand ? '' : 'disabled' }}>
                                <option value="">Select Device</option>
                                @if($selectedBrand)
                                    @foreach($selectedBrand->devices as $device)
                                        <option value="{{ $device->id }}" {{ (int) $selectedDeviceId === (int) $device->id ? 'selected' : '' }}>
                                            {{ $device->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Review Title & Slug -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Review Title *</label>
                            <input type="text" class="form-control" name="review[title]" id="review_title"
                                value="{{ old('review.title', $isEdit ? $review->title : '') }}"
                                placeholder="e.g., Apple iPhone 17 Pro Max Review" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Review Slug *</label>
                            <input type="text" class="form-control" name="review[slug]" id="review_slug"
                                value="{{ old('review.slug', $isEdit ? $review->slug : '') }}"
                                placeholder="e.g., apple-iphone-17-pro-max-review" required>
                        </div>
                    </div>
                </div>

                <!-- Media & Date -->
                <div class="row mb-4 align-items-end">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label d-block">Cover Image *</label>
                            <x-image-upload fieldName="cover_image" :existingImage="$isEdit ? $review->cover_image_url : null" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Publish Date</label>
                            <input type="datetime-local" class="form-control" name="review[published_at]"
                                value="{{ old('review.published_at', ($isEdit && $review->published_at) ? $review->published_at->format('Y-m-d\TH:i') : '') }}">
                            <small class="text-muted">Leave empty for instant publication if 'Publish Review' is
                                checked.</small>
                        </div>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Review Content *</label>
                            <textarea class="form-control review-content" name="review[body]"
                                placeholder="Write detailed review content here...">{{ old('review.body', $isEdit ? $review->body : '') }}</textarea>
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

    <script>
        $(document).ready(function () {
            $('.review-content').summernote({
                height: 400,
                placeholder: 'Start writing your professional review...',
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
                ]
            });
        });

        // Brand selection dynamic device loading
        document.addEventListener('DOMContentLoaded', function () {
            const brandSelect = document.querySelector('.brand-select');
            const deviceSelect = document.querySelector('.device-select');

            if (!brandSelect || !deviceSelect) return;

            brandSelect.addEventListener('change', function () {
                const brandId = this.value;
                deviceSelect.innerHTML = '<option value="">Select Device</option>';

                if (!brandId) {
                    deviceSelect.disabled = true;
                    return;
                }

                deviceSelect.disabled = true;
                deviceSelect.innerHTML = '<option value="">Loading devices...</option>';

                fetch(`/admin/brands/${brandId}/devices`)
                    .then(response => response.json())
                    .then(devices => {
                        deviceSelect.disabled = false;
                        deviceSelect.innerHTML = '<option value="">Select Device</option>';
                        devices.forEach(device => {
                            const option = document.createElement('option');
                            option.value = device.id;
                            option.textContent = device.name;
                            deviceSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        deviceSelect.disabled = false;
                        deviceSelect.innerHTML = '<option value="">Error loading devices</option>';
                    });
            });
        });

        // Slug generation
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('review_title');
            const slugInput = document.getElementById('review_slug');
            let isAutoSlug = {{ $isEdit ? 'false' : 'true' }};

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    if (isAutoSlug) {
                        slugInput.value = slugify(this.value);
                    }
                });

                slugInput.addEventListener('input', function () {
                    isAutoSlug = false;
                    if (this.value.trim() === '') {
                        isAutoSlug = true;
                        slugInput.value = slugify(titleInput.value);
                    }
                });
            }

            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }
        });
    </script>
@endpush
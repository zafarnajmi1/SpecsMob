@extends('admin-views.layouts.admin')
@section('title', 'Edit Article')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .form-control, .form-select, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        color: #000 !important;
    }
    .text-danger {
        color: #ff0000 !important;
    }
    .form-label {
        color: #000 !important;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Article</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Articles</a></li>
                            <li class="breadcrumb-item active">Edit Article</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Article Content</h5>
                            </div>
                            <div class="card-body">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Article Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $article->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                               

                                <!-- Body Content -->
                                <div class="mb-3">
                                    <label for="body" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" 
                                              id="body" name="body" rows="15" required>{{ old('body', $article->body) }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                   <!-- Include SEO Fields -->
                                 <div class="row mb-4">
                                    <div class="col-12">
                                         @include('components.seo-fields', ['model' => $article ?? null])
                                    </div>
                                </div>


                                 <div class="row mb-4">
                                    <div class="col-12">
                                         <div class="d-flex justify-content-end gap-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save me-1"></i> Update Article
                                    </button>
                                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-danger">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </a>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Publish Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Publish Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Article Type -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">Article Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="news" {{ old('type', $article->type) == 'news' ? 'selected' : '' }}>News</option>
                                        <option value="article" {{ old('type', $article->type) == 'article' ? 'selected' : '' }}>Blog Post</option>
                                        <option value="featured" {{ old('type', $article->type) == 'featured' ? 'selected' : '' }}>Featured Content</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_published" 
                                                   id="published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="published">Published</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_published" 
                                                   id="draft" value="0" {{ !old('is_published', $article->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="draft">Draft</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Publish Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                           id="published_at" name="published_at" 
                                           value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                                         </div>

                                <!-- Featured -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_featured" name="is_featured" value="1" 
                                               {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Mark as Featured</label>
                                    </div>
                                </div>

                                <!-- Comments -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="allow_comments" name="allow_comments" value="1" 
                                               {{ old('allow_comments', $article->allow_comments) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_comments">Allow Comments</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Featured Image</h5>
                            </div>
                            <div class="card-body">
                                @if($article->thumbnail_url)
                                <div class="mb-3 text-center">
                                    <p class="text-muted small">Current Thumbnail:</p>
                                    <img src="{{ $article->thumbnail_url }}" 
                                         alt="{{ $article->title }}" 
                                         class="img-fluid rounded mb-2 border" 
                                         style="max-height: 150px;">
                                    <div class="form-check d-flex justify-content-center mt-2">
                                        <input class="form-check-input me-2" type="checkbox" 
                                               id="remove_thumbnail" name="remove_thumbnail" value="1"
                                               onchange="handleRemoveImageChange()">
                                        <label class="form-check-label text-danger" for="remove_thumbnail">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                                @else
                                <div class="mb-3 text-center">
                                    <div class="bg-light p-4 rounded border-2 border-dashed" style="border: 2px dashed #dee2e6;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">No image uploaded</p>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">{{ $article->thumbnail_url ? 'Upload New Thumbnail' : 'Upload Thumbnail' }}</label>
                                    <input type="file" class="form-control @error('thumbnail_url') is-invalid @enderror" 
                                           id="thumbnail" name="thumbnail_url" accept="image/*"
                                           onchange="handleImagePreview(event)">
                                    <small class="form-text text-muted d-block mt-2">Recommended size: 800x450px. Max file size: 2MB</small>
                                    @error('thumbnail_url')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                    <p class="text-muted small">New Preview:</p>
                                    <img id="previewImage" class="img-fluid rounded border" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>

                        <!-- Categories & Tags -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Categories & Tags</h5>
                            </div>
                            <div class="card-body">
                                <!-- Brand -->
                              <!-- Brand -->
<div class="mb-3 brand-device-group">
    <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
    <select
        class="form-select brand-select"
        name="brand_id"
        id="brand_id"
        required
    >
        <option value="">Select Brand</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}"
                {{ (string) old('brand_id', $article->brand_id) === (string) $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Device -->
<div class="mb-3 brand-device-group">
    <label for="device_id" class="form-label">Device <span class="text-danger">*</span></label>
    <select
        class="form-select device-select"
        name="device_id"
        id="device_id"
        required
    >
        <option value="">Select Device</option>

        {{-- Pre-populate devices for the current brand --}}
        @foreach($devices as $device)
            <option value="{{ $device->id }}"
                {{ (string) old('device_id', $article->device_id) === (string) $device->id ? 'selected' : '' }}>
                {{ $device->name }}
            </option>
        @endforeach
    </select>
</div>

                                <!-- Tags -->
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="tags" name="tags[]" multiple required>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Summernote with existing content
        $('#body').summernote({
            height: 400,
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

        // Initialize Select2
        $('.select2').select2({
            tags: true,
            placeholder: 'Select options',
            allowClear: true
        });

        // Character counters
        $('#meta_title').on('input', function() {
            $('#metaTitleCount').text($(this).val().length);
        });

        $('#meta_description').on('input', function() {
            $('#metaDescCount').text($(this).val().length);
        });
    });

    // Image preview handler
    function handleImagePreview(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(file);
        }
    }

    // Handle remove image checkbox
    function handleRemoveImageChange() {
        const isChecked = document.getElementById('remove_thumbnail').checked;
        const thumbnailInput = document.getElementById('thumbnail');
        
        if (isChecked) {
            thumbnailInput.disabled = true;
            thumbnailInput.classList.add('opacity-50');
        } else {
            thumbnailInput.disabled = false;
            thumbnailInput.classList.remove('opacity-50');
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const brandSelect = document.querySelector('.brand-select');
        const deviceSelect = document.querySelector('.device-select');
        const currentDeviceId = '{{ $article->device_id ?? "" }}';

        if (!brandSelect || !deviceSelect) return;

        function loadDevices(brandId, selectDeviceId = null) {
            deviceSelect.innerHTML = '<option value="">Select Device</option>';

            if (!brandId) {
                deviceSelect.disabled = false;
                return;
            }

            deviceSelect.disabled = true;
            deviceSelect.innerHTML = '<option value="">Loading devices...</option>';

            fetch(`/admin/brands/${brandId}/devices`)
                .then(response => response.json())
                .then(devices => {
                    deviceSelect.disabled = false;
                    deviceSelect.innerHTML = '<option value="">Select Device</option>';

                    if (devices.length > 0) {
                        devices.forEach(device => {
                            const option = document.createElement('option');
                            option.value = device.id;
                            option.textContent = device.name;
                            
                            // If this device matches the one we want to select, mark it
                            if (selectDeviceId && device.id == selectDeviceId) {
                                option.selected = true;
                            }
                            
                            deviceSelect.appendChild(option);
                        });
                    } else {
                        deviceSelect.innerHTML = '<option value="">No devices found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching devices:', error);
                    deviceSelect.disabled = false;
                    deviceSelect.innerHTML = '<option value="">Error loading devices</option>';
                });
        }

        // Load devices on page load if brand is already selected (edit mode)
        if (brandSelect.value) {
            loadDevices(brandSelect.value, currentDeviceId);
        }

        // Handle brand change
        brandSelect.addEventListener('change', function () {
            const brandId = this.value;
            loadDevices(brandId);
        });
    });
</script>

@endpush
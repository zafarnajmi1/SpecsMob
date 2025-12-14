@extends('admin-views.layouts.admin')
@section('title', 'Create New Article')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Create New Article</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Articles</a></li>
                            <li class="breadcrumb-item active">Create Article</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Article Content</h5>
                            </div>
                            <div class="card-body">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Article Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug *</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" name="slug" value="{{ old('slug') }}" required>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">URL-friendly version of the title</div>
                                </div>

                                <!-- Body Content -->
                                <div class="mb-3">
                                    <label for="body" class="form-label">Content *</label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" 
                                              id="body" name="body" rows="15">{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Include SEO Fields -->
                                 <div class="row mb-4">
                                    <div class="col-12">
                                         @include('components.seo-fields', ['model' => null])
                                    </div>
                                </div>

                                 <div class="row mb-4">
                                    <div class="col-12">
                                         <div class="d-flex justify-content-end gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Create Article
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
                                    <label for="type" class="form-label">Article Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News</option>
                                        <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Blog Post</option>
                                        <option value="featured" {{ old('type') == 'featured' ? 'selected' : '' }}>Featured Content</option>
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
                                                   id="published" value="1" {{ old('is_published', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="published">Published</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_published" 
                                                   id="draft" value="0" {{ !old('is_published', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="draft">Draft</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Publish Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                           id="published_at" name="published_at" 
                                           value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Featured -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="is_featured" name="is_featured" value="1" 
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Mark as Featured</label>
                                    </div>
                                </div>

                                <!-- Comments -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="allow_comments" name="allow_comments" value="1" 
                                               {{ old('allow_comments', true) ? 'checked' : '' }}>
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
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Upload Thumbnail</label>
                                    <input type="file" class="form-control @error('thumbnail_url') is-invalid @enderror" 
                                           id="thumbnail" name="thumbnail_url" accept="image/*">
                                    @error('thumbnail_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" class="mt-2 text-center" style="display: none;">
                                    <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
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
                               <div class="mb-3 brand-device-group">
    <label for="brand_id" class="form-label">Brand</label>
    <select
        class="form-select brand-select"
        name="brand_id"
        required
    >
        <option value="">Select Brand</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}"
                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3 brand-device-group">
    <label for="device_id" class="form-label">Device</label>
    <select
        class="form-select device-select"
        name="device_id"
        required
    >
        <option value="">Select Device</option>
        {{-- Will be filled dynamically based on selected brand --}}
    </select>
</div>
                                <!-- Tags -->
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-select select2" id="tags" name="tags[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select or type to create new tags</div>
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
        // Initialize Summernote
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
            ],
            callbacks: {
                onChange: function(contents) {
                    // Auto-generate excerpt if empty
                    if (!$('#excerpt').val()) {
                        const plainText = $(contents).text().substring(0, 150);
                        $('#excerpt').val(plainText + '...');
                    }
                }
            }
        });

        // Initialize Select2
        $('.select2').select2({
            tags: true,
            placeholder: 'Select options',
            allowClear: true
        });

        // Auto-generate slug from title
        $('#title').on('blur', function() {
            if (!$('#slug').val()) {
                const slug = generateSlug($(this).val());
                $('#slug').val(slug);
            }
            // Auto-generate meta title if empty
            if (!$('#meta_title').val()) {
                $('#meta_title').val($(this).val());
                updateCharacterCount('#meta_title', '#metaTitleCount');
            }
        });

        // Character counters
        $('#meta_title').on('input', function() {
            updateCharacterCount('#meta_title', '#metaTitleCount');
        });

        $('#meta_description').on('input', function() {
            updateCharacterCount('#meta_description', '#metaDescCount');
        });

        // Image preview
        $('#thumbnail').change(function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(file);
            }
        });

        function generateSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^\w\s-]+/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function updateCharacterCount(inputSelector, countSelector) {
            const text = $(inputSelector).val();
            $(countSelector).text(text.length);
        }

        // Initialize counts
        updateCharacterCount('#meta_title', '#metaTitleCount');
        updateCharacterCount('#meta_description', '#metaDescCount');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const brandSelect = document.querySelector('.brand-select');
        const deviceSelect = document.querySelector('.device-select');

        if (!brandSelect || !deviceSelect) return;

        brandSelect.addEventListener('change', function () {
            const brandId = this.value;

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
        });
    });
</script>

@endpush
@php
    /** @var \App\Models\Video|null $video */
    $isEdit = isset($video);
@endphp

<!-- Section 1: Individual Videos -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light-primary d-flex justify-content-between align-items-center">
                <h5 class="card-title text-primary mb-0">
                    <i class="bi bi-play-circle me-2"></i>Videos Collection
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addVideo">
                    <i class="bi bi-plus-circle me-1"></i> Add Video
                </button>
            </div>
            <div class="card-body">
                <div id="videosContainer">
                    @if($isEdit && $video->videoItems && $video->videoItems->count() > 0)
                        <!-- Show existing videos for editing -->
                        @foreach($video->videoItems as $videoIndex => $videoItem)
                        <div class="video-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="videos[{{$videoIndex}}][id]" value="{{ $videoItem->id }}">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <select class="form-select brand-select" name="videos[{{ $videoIndex }}][brand_id]" required data-video-index="{{ $videoIndex }}">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ old("videos.{$videoIndex}.brand_id", $videoItem->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Device <span class="text-danger">*</span></label>
                                            <select class="form-select device-select" name="videos[{{ $videoIndex }}][device_id]" required data-video-index="{{ $videoIndex }}">
                                                <option value="">Select Device</option>
                                                @if($videoItem->brand_id)
                                                    @php
                                                        $selectedBrand = $brands->firstWhere('id', $videoItem->brand_id);
                                                    @endphp
                                                    @if($selectedBrand)
                                                        @foreach($selectedBrand->devices as $device)
                                                            <option value="{{ $device->id }}"
                                                                {{ old("videos.{$videoIndex}.device_id", $videoItem->device_id) == $device->id ? 'selected' : '' }}>
                                                                {{ $device->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Video Title</label>
                                            <input type="text" class="form-control video-title" name="videos[{{ $videoIndex }}][title]" value="{{ $videoItem->title }}" placeholder="Enter video title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">YouTube URL or ID</label>
                                            <input type="text" class="form-control youtube-url" name="videos[{{ $videoIndex }}][youtube_id]" value="{{ $videoItem->youtube_id }}" placeholder="e.g., https://youtube.com/watch?v=ABC123 or ABC123" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control video-description" name="videos[{{ $videoIndex }}][description]" rows="2" placeholder="Optional video description">{{ $videoItem->description }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Thumbnail Upload for Each Video -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Video Thumbnail</label>
                                            <x-image-upload 
                                                fieldName="videos[{{ $videoIndex }}][thumbnail]" 
                                                :existingImage="$videoItem->thumbnail_url ?? null" 
                                            />
                                            <small class="text-muted d-block mt-1">
                                                Upload custom thumbnail or leave empty to use YouTube thumbnail
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Video Preview</label>
                                            <div class="video-preview embed-responsive embed-responsive-16by9 bg-light rounded" style="min-height: 300px; position: relative; width: 100%;">
                                                @php
                                                    $videoId = extractYouTubeId($videoItem->youtube_id);
                                                @endphp
                                                @if($videoId)
                                                    <iframe 
                                                        src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                        class="embed-responsive-item" 
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen
                                                        style="position:absolute; width: 100%; height: 100%; border-radius: 0.375rem;">
                                                    </iframe>
                                                @else
                                                    <div class="embed-responsive-item d-flex align-items-center justify-content-center text-muted position-absolute top-0 left-0 h-100 w-100">
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
                                                <input class="form-check-input" type="checkbox" name="videos[{{ $videoIndex }}][is_published]" value="1" {{ $videoItem->is_published ? 'checked' : '' }}>
                                                <label class="form-check-label">Publish this video</label>
                                            </div>
                                            <small class="text-muted d-block mt-2">Enter YouTube URL and click "Preview" to see the video</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Show empty state for new video page -->
                        <div class="text-center py-4 text-muted" id="noVideosMessage">
                            <i class="bi bi-play-circle display-4"></i>
                            <p class="mt-2 mb-0">No videos added yet</p>
                            <small>Click "Add Video" to add videos with brand and device information</small>
                        </div>
                    @endif
                </div>

                <!-- Video Template for new videos -->
                <template id="videoTemplate">
                    <div class="video-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Brand <span class="text-danger">*</span></label>
                                        <select class="form-select brand-select" name="videos[INDEX][brand_id]" required data-video-index="INDEX">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Device <span class="text-danger">*</span></label>
                                        <select class="form-select device-select" name="videos[INDEX][device_id]" required data-video-index="INDEX">
                                            <option value="">Select Device</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Title</label>
                                        <input type="text" class="form-control video-title" name="videos[INDEX][title]" placeholder="Enter video title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">YouTube URL or ID</label>
                                        <input type="text" class="form-control youtube-url" name="videos[INDEX][youtube_id]" placeholder="e.g., https://youtube.com/watch?v=ABC123 or ABC123" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control video-description" name="videos[INDEX][description]" rows="2" placeholder="Optional video description"></textarea>
                                    </div>
                                </div>

                                <!-- Thumbnail Upload for New Videos -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Video Thumbnail</label>
                                        <div class="thumbnail-upload-container">
                                            <input type="file" class="form-control video-thumbnail-input" name="videos[INDEX][thumbnail]" accept="image/*">
                                            <div class="thumbnail-preview mt-2 text-center" style="display: none;">
                                                <img src="" class="img-fluid rounded border" alt="Thumbnail preview" style="max-height: 150px;">
                                            </div>
                                            <div class="default-thumbnail mt-2 text-center">
                                                <div class="border rounded p-3 bg-light">
                                                    <i class="bi bi-image display-6 text-muted"></i>
                                                    <p class="mt-2 mb-0 text-muted">No thumbnail uploaded</p>
                                                    <small class="text-muted">YouTube thumbnail will be used</small>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            Upload custom thumbnail or leave empty to use YouTube thumbnail
                                        </small>
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
                                            <input class="form-check-input" type="checkbox" name="videos[INDEX][is_published]" value="1" checked>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counter
    @if($isEdit && $video->videoItems && $video->videoItems->count() > 0)
        let videoCounter = {{ $video->videoItems->count() }};
    @else
        let videoCounter = 0;
    @endif

    // Add new video
    document.getElementById('addVideo').addEventListener('click', function() {
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
                input.name = input.name.replace('videos[INDEX]', `videos[${videoCounter}]`);
            }
            if (input.hasAttribute('data-video-index')) {
                input.setAttribute('data-video-index', videoCounter);
            }
        });

        document.getElementById('videosContainer').appendChild(videoItem);
        
        // Hide no videos message
        const noVideosMessage = document.getElementById('noVideosMessage');
        if (noVideosMessage) {
            noVideosMessage.style.display = 'none';
        }
        
        // Initialize brand-device dropdown for new video
        initBrandDeviceDropdown(videoItem);
        
        // Initialize thumbnail upload for new video
        initThumbnailUpload(videoItem);
        
        videoCounter++;
    }

    // Brand-Device dynamic dropdown functionality
    function initBrandDeviceDropdown(container) {
        const brandSelect = container.querySelector('.brand-select');
        const deviceSelect = container.querySelector('.device-select');
        
        if (brandSelect && deviceSelect) {
            brandSelect.addEventListener('change', function() {
                const brandId = this.value;
                const videoIndex = this.getAttribute('data-video-index');
                const deviceSelect = container.querySelector(`.device-select[data-video-index="${videoIndex}"]`);
                
                // Clear device options
                deviceSelect.innerHTML = '<option value="">Select Device</option>';
                
                if (brandId) {
                    // Show loading state
                    deviceSelect.disabled = true;
                    deviceSelect.innerHTML = '<option value="">Loading devices...</option>';
                    
                    // Fetch devices for selected brand
                    fetch(`/admin/brands/${brandId}/devices`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
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
                } else {
                    deviceSelect.disabled = false;
                }
            });
        }
    }

    // Thumbnail upload functionality
    function initThumbnailUpload(container) {
        const thumbnailInput = container.querySelector('.video-thumbnail-input');
        const thumbnailPreview = container.querySelector('.thumbnail-preview');
        const defaultThumbnail = container.querySelector('.default-thumbnail');
        const previewImg = thumbnailPreview.querySelector('img');
        
        if (thumbnailInput && thumbnailPreview && defaultThumbnail) {
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        thumbnailPreview.style.display = 'block';
                        defaultThumbnail.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else {
                    thumbnailPreview.style.display = 'none';
                    defaultThumbnail.style.display = 'block';
                }
            });
        }
    }

    // Initialize existing brand-device dropdowns
    document.querySelectorAll('.video-item').forEach(videoItem => {
        initBrandDeviceDropdown(videoItem);
        
        // Initialize thumbnail upload for existing videos (if they have custom thumbnail inputs)
        const thumbnailInput = videoItem.querySelector('.video-thumbnail-input');
        if (thumbnailInput) {
            initThumbnailUpload(videoItem);
        }
    });

    // Remove video function
    document.addEventListener('click', function(e) {
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

    // YouTube preview functions
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

    // Auto-preview when YouTube URL is entered
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('youtube-url')) {
            const youtubeUrl = e.target.value;
            if (youtubeUrl.length > 20) {
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
@endpush
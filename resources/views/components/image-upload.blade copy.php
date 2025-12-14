<div class="form-group">
    <label for="{{ $fieldName }}" class="form-label">{{ ucfirst(str_replace('_', ' ', $fieldName)) }}</label>
    
    <!-- Upload Area -->
    <div class="upload-area" id="{{ $fieldName }}UploadArea">
        <div class="upload-placeholder">
            <i class="bi bi-image display-4 text-muted"></i>
            <p class="mt-2 mb-1 text-muted">Click to upload image</p>
            <small class="text-muted">PNG, JPG up to 2MB</small>
        </div>
        <input type="file" class="form-control d-none" id="{{ $fieldName }}" name="{{ $fieldName }}" accept="image/*">
    </div>

    <!-- Preview Area -->
    <div class="preview-area mt-2" id="{{ $fieldName }}PreviewArea" style="display: none;">
        @if ($existingImage)
            <div class="preview-container position-relative d-inline-block">
                <img id="{{ $fieldName }}Preview" src="{{ asset('storage/' . $existingImage) }}" alt="{{ ucfirst($fieldName) }} Preview"
                    class="img-thumbnail rounded" style="max-width: 150px; max-height: 150px; object-fit: contain;">
            </div>
        @else
            <div class="preview-container position-relative d-inline-block">
                <img id="{{ $fieldName }}Preview" src="#" alt="{{ ucfirst($fieldName) }} Preview"
                    class="img-thumbnail rounded" style="max-width: 150px; max-height: 150px; object-fit: contain;">
            </div>
        @endif
        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" id="remove{{ ucfirst($fieldName) }}" title="Remove image">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <small class="text-muted mt-2 d-block">Click the image to change</small>
</div>

@push('styles')
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background: #e7f1ff;
        }

        .preview-area {
            transition: all 0.3s ease;
        }

        .preview-container {
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Common image upload handler
            function handleImageUpload(input, uploadArea, previewArea, previewImage) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('Please select a valid image file (PNG, JPG, JPEG)');
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        uploadArea.style.display = 'none';
                        previewArea.style.display = 'block';
                    }

                    reader.readAsDataURL(file);
                }
            }

            // Handle image upload (common for logo and cover image)
            const uploadArea = document.getElementById('{{ $fieldName }}UploadArea');
            const previewArea = document.getElementById('{{ $fieldName }}PreviewArea');
            const previewImage = document.getElementById('{{ $fieldName }}Preview');
            const inputElement = document.getElementById('{{ $fieldName }}');

            uploadArea.addEventListener('click', function () {
                inputElement.click();
            });

            inputElement.addEventListener('change', function () {
                handleImageUpload(inputElement, uploadArea, previewArea, previewImage);
            });

            // Remove image functionality
            document.getElementById('remove{{ ucfirst($fieldName) }}').addEventListener('click', function (e) {
                e.stopPropagation();
                inputElement.value = '';
                previewArea.style.display = 'none';
                uploadArea.style.display = 'flex';
            });

            previewImage.addEventListener('click', function () {
                inputElement.click();
            });
        });
    </script>
@endpush

@props([
    'fieldName',
    'existingImage' => null,
])

@php
    $id = uniqid($fieldName . '_'); // Unique ID to avoid conflicts
    $hasImage = !empty($existingImage);
    $previewSrc = $hasImage ? $existingImage : '#';
@endphp

<div class="form-group" data-image-upload="{{ $id }}">
    <label for="{{ $id }}_input" class="form-label">
        {{ ucfirst(str_replace('_', ' ', $fieldName)) }}
    </label>
    <!-- Upload Box -->
    <div id="{{ $id }}_uploadArea"
         class="border rounded p-3 text-center cursor-pointer"
         style="{{ $hasImage ? 'display:none;' : '' }}">
        <i class="bi bi-image display-5 text-muted"></i>
        <p class="text-muted mb-1">Click to upload image</p>
        <small class="text-muted">PNG, JPG up to 2MB</small>

        <input type="file"
               id="{{ $id }}_input"
               name="{{ $fieldName }}"
               accept="image/*"
               class="d-none">
    </div>

    <!-- Preview Area -->
    <div id="{{ $id }}_previewArea" class="mt-2" style="{{ $hasImage ? '' : 'display:none;' }}">
        <div class="position-relative d-inline-block">
            <img id="{{ $id }}_preview"
                 src="{{ $previewSrc }}"
                 class="img-thumbnail rounded"
                 style="max-width: 150px; max-height: 150px; object-fit: contain;">

            <button type="button"
                    id="{{ $id }}_remove"
                    class="btn btn-sm btn-danger position-absolute top-0 end-0">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
</div>


{{-- Component JavaScript --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const uploadArea  = document.getElementById("{{ $id }}_uploadArea");
    const input       = document.getElementById("{{ $id }}_input");
    const previewArea = document.getElementById("{{ $id }}_previewArea");
    const previewImg  = document.getElementById("{{ $id }}_preview");
    const removeBtn   = document.getElementById("{{ $id }}_remove");

    // Click to open file
    uploadArea.addEventListener("click", () => input.click());

    // On file selection
    input.addEventListener("change", function () {
        if (!this.files || !this.files[0]) return;

        const file = this.files[0];

        if (!file.type.startsWith("image/")) {
            alert("Please select a valid image file.");
            this.value = "";
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert("Image must be less than 2MB.");
            this.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            previewArea.style.display = "block";
            uploadArea.style.display = "none";
        };

        reader.readAsDataURL(file);
    });

    // Remove image
    removeBtn.addEventListener("click", function () {
        input.value = "";
        previewImg.src = "";
        previewArea.style.display = "none";
        uploadArea.style.display = "block";
    });
});
</script>

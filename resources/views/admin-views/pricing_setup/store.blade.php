@extends('admin-views.layouts.admin')
@section('title', 'Stores List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Stores</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Stores</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Stores List</h4>

                {{-- CREATE button (opens modal in "create" mode) --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeModal"
                    data-mode="create">
                    <i class="bi bi-plus"></i> Add New Store
                </button>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Logo</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->slug }}</td>
                                <td>
                                    @if($store->logo_url)
                                        <img src="{{ asset($store->logo_url) }}" alt="{{ $store->name }}"
                                            class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $store->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">

                                        {{-- EDIT button (opens same modal in "edit" mode) --}}
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#storeModal" data-mode="edit" data-id="{{ $store->id }}"
                                            data-name="{{ $store->name }}"
                                            data-logo-url="{{ $store->logo_url }}"
                                            data-update-url="{{ route('admin.stores.update', $store->id) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm confirm-delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No stores found.
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#storeModal"
                                        data-mode="create">
                                        Create the first store
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- SINGLE REUSABLE MODAL --}}
    <div class="modal fade text-left" id="storeModal" tabindex="-1" role="dialog"
        aria-labelledby="storeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form id="storeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- _method will be changed via JS (POST / PUT) --}}
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="storeModalLabel">Add New Store</h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-body">
                            <div class="row">

                                {{-- Name --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="storeName">Name</label>
                                        <input type="text" id="storeName"
                                            class="form-control" name="name"
                                            placeholder="Store name" value="{{ old('name') }}">
                                    </div>
                                </div>

                                {{-- Logo --}}
                                <div class="col-12 mb-3">
                                    <div id="logoUploadContainer">
                                        <!-- Logo upload component will be dynamically inserted here -->
                                        <x-image-upload fieldName="logo" :existingImage="null" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary me-1 mb-1">
                            Save
                        </button>
                        <button type="button" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        // Simple Datatable
        const table1 = document.querySelector('#table1');
        if (table1) {
            new simpleDatatables.DataTable(table1);
        }

        // Function to initialize image upload component
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

            // Remove existing event listeners by cloning and replacing
            const newUploadArea = uploadArea.cloneNode(true);
            uploadArea.parentNode.replaceChild(newUploadArea, uploadArea);

            const newPreviewArea = previewArea.cloneNode(true);
            previewArea.parentNode.replaceChild(newPreviewArea, previewArea);

            // Get new references after replacement
            const newUploadAreaRef = document.getElementById(fieldName + 'UploadArea');
            const newPreviewAreaRef = document.getElementById(fieldName + 'PreviewArea');
            const newPreviewImageRef = document.getElementById(fieldName + 'Preview');
            const newInputElementRef = document.getElementById(fieldName);
            const newRemoveBtnRef = document.getElementById('remove' + fieldName.charAt(0).toUpperCase() + fieldName.slice(1));

            // Add event listeners
            newUploadAreaRef.addEventListener('click', function () {
                newInputElementRef.click();
            });

            newInputElementRef.addEventListener('change', function () {
                handleImageUpload(newInputElementRef, newUploadAreaRef, newPreviewAreaRef, newPreviewImageRef);
            });

            newRemoveBtnRef.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                newInputElementRef.value = '';
                newPreviewAreaRef.style.display = 'none';
                newUploadAreaRef.style.display = 'flex';
                newPreviewImageRef.src = '';
            });

            newPreviewImageRef.addEventListener('click', function () {
                newInputElementRef.click();
            });
        }

        // One modal for CREATE + EDIT
        const storeModal = document.getElementById('storeModal');

        if (storeModal) {
            storeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const mode = button.getAttribute('data-mode') || 'create';

                const form = document.getElementById('storeForm');
                const methodInput = document.getElementById('formMethod');
                const nameInput = document.getElementById('storeName');
                const logoContainer = document.getElementById('logoUploadContainer');
                const title = document.getElementById('storeModalLabel');

                if (mode === 'edit') {
                    const updateUrl = button.getAttribute('data-update-url');
                    const name = button.getAttribute('data-name');
                    const logoUrl = button.getAttribute('data-logo-url');

                    form.action = updateUrl;
                    methodInput.value = 'PUT';
                    title.textContent = 'Edit Store';

                    nameInput.value = name || '';

                    logoContainer.innerHTML = '';
                    
                    const newLogoUpload = document.createElement('div');
                    newLogoUpload.innerHTML = `
                        <div class="form-group">
                            <label for="storeLogo">Logo</label>
                            <input type="file" id="storeLogo" class="form-control" name="logo" accept="image/*">
                            
                            ${logoUrl ? `
                            <div id="logoPreview" class="mt-2">
                                <img id="logoPreviewImage" src="${logoUrl}" alt="Logo preview" 
                                     class="img-thumbnail" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                                <div class="mt-1">
                                    <button type="button" class="btn btn-sm btn-danger" id="removeLogoPreview">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            ` : ''}
                            
                            <small class="text-muted d-block mt-1">PNG, JPG up to 2MB</small>
                        </div>
                    `;
                    logoContainer.appendChild(newLogoUpload);

                    // Add event listeners for the new file input
                    setTimeout(() => {
                        const newLogoInput = document.getElementById('storeLogo');
                        const newLogoPreview = document.getElementById('logoPreview');
                        const newLogoPreviewImage = document.getElementById('logoPreviewImage');
                        const newRemoveBtn = document.getElementById('removeLogoPreview');

                        if (newLogoInput && newLogoPreview) {
                            newLogoInput.addEventListener('change', function(e) {
                                if (this.files && this.files[0]) {
                                    const file = this.files[0];
                                    
                                    if (!file.type.match('image.*')) {
                                        alert('Please select a valid image file (PNG, JPG, JPEG)');
                                        this.value = '';
                                        return;
                                    }

                                    if (file.size > 2 * 1024 * 1024) {
                                        alert('File size must be less than 2MB');
                                        this.value = '';
                                        return;
                                    }

                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        newLogoPreviewImage.src = e.target.result;
                                        newLogoPreview.style.display = 'block';
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        }

                        if (newRemoveBtn) {
                            newRemoveBtn.addEventListener('click', function() {
                                const logoInput = document.getElementById('storeLogo');
                                const logoPreview = document.getElementById('logoPreview');
                                
                                logoInput.value = '';
                                if (logoPreview) {
                                    logoPreview.style.display = 'none';
                                }
                            });
                        }
                    }, 100);

                } else {
                    // CREATE mode - use the image-upload component
                    form.action = "{{ route('admin.stores.store') }}";
                    methodInput.value = 'POST';
                    title.textContent = 'Add New Store';

                    nameInput.value = '';

                    // Reset to use image-upload component
                    logoContainer.innerHTML = '';
                    const newLogoUpload = document.createElement('div');
                    newLogoUpload.innerHTML = `
                        <x-image-upload fieldName="logo" :existingImage="null" />
                    `;
                    logoContainer.appendChild(newLogoUpload);

                    // Initialize the image upload component after it's inserted
                    setTimeout(() => {
                        initializeImageUpload('logo');
                    }, 100);
                }
            });
        }

        // Initialize image upload on page load for the initial component
        document.addEventListener('DOMContentLoaded', function() {
            initializeImageUpload('logo');
        });

        // Delete confirmation
        document.querySelectorAll('.confirm-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (confirm('Are you sure you want to delete this store?')) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
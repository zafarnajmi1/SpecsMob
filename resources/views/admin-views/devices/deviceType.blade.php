@extends('admin-views.layouts.admin')
@section('title', 'DeviceType List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All DeviceTypes</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">DeviceTypes</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">DeviceTypes List</h4>

                {{-- CREATE button (opens modal in "create" mode) --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deviceTypeModal"
                    data-mode="create">
                    <i class="bi bi-plus"></i> Add New DeviceType
                </button>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deviceTypes as $deviceType)
                            <tr>
                                <td>{{ $deviceType->name }}</td>
                                <td>{{ $deviceType->slug }}</td>
                                <td>
                                    @if($deviceType->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $deviceType->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">

                                        {{-- EDIT button (opens same modal in "edit" mode) --}}
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#deviceTypeModal" data-mode="edit" data-id="{{ $deviceType->id }}"
                                            data-name="{{ $deviceType->name }}" data-status="{{ $deviceType->status }}"
                                            data-update-url="{{ route('admin.devicetypes.update', $deviceType->id) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.devicetypes.destroy', $deviceType->id) }}" method="POST"
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
                                <td colspan="5" class="text-center">
                                    No deviceTypes found.
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deviceTypeModal"
                                        data-mode="create">
                                        Create the first deviceType
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
    <div class="modal fade text-left" id="deviceTypeModal" tabindex="-1" role="dialog"
        aria-labelledby="deviceTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form id="deviceTypeForm" method="POST">
                    @csrf
                    {{-- _method will be changed via JS (POST / PUT) --}}
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="deviceTypeModalLabel">Add New DeviceType</h5>
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
                                        <label for="deviceTypeName">Name</label>
                                        <input type="text" id="deviceTypeName"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            placeholder="Device type name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="deviceTypeStatus">Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror"
                                            id="deviceTypeStatus" name="status">
                                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

        // One modal for CREATE + EDIT
        const deviceTypeModal = document.getElementById('deviceTypeModal');

        if (deviceTypeModal) {
            deviceTypeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const mode = button.getAttribute('data-mode') || 'create';

                const form = document.getElementById('deviceTypeForm');
                const methodInput = document.getElementById('formMethod');
                const nameInput = document.getElementById('deviceTypeName');
                const statusSelect = document.getElementById('deviceTypeStatus');
                const title = document.getElementById('deviceTypeModalLabel');

                if (mode === 'edit') {
                    const updateUrl = button.getAttribute('data-update-url');
                    const name = button.getAttribute('data-name');
                    const status = button.getAttribute('data-status');

                    form.action = updateUrl;
                    methodInput.value = 'PUT';
                    title.textContent = 'Edit DeviceType';

                    nameInput.value = name || '';
                    statusSelect.value = status === '0' ? '0' : '1';
                } else {
                    // CREATE mode
                    form.action = "{{ route('admin.devicetypes.store') }}";
                    methodInput.value = 'POST';
                    title.textContent = 'Add New DeviceType';

                    nameInput.value = '';
                    statusSelect.value = '1';
                }
            });
        }
    </script>
@endpush
@extends('admin-views.layouts.admin')
@section('title', 'Devices List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Devices</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Devices</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Devices List</h4>
            <a href="{{ route('admin.devices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Device
            </a>
        </div>

        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Quick Specs</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($devices as $device)
                        <tr>
                             <td>{{ $loop->iteration }}</td>
                            <!-- Thumbnail -->
                            <td>
                                @if($device->thumbnail_url)
                                    <img src="{{ asset('storage/' . $device->thumbnail_url) }}"
                                         alt="{{ $device->name }}"
                                         width="60" height="60"
                                         style="object-fit: cover; border-radius:8px;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                         style="width:60px; height:60px; border-radius:8px;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Name -->
                            <td>{{ $device->name }}</td>

                            <!-- Slug -->
                            <td>{{ $device->slug }}</td>

                            <!-- Quick Specs -->
                            <td style="font-size: 12px;">
                                <strong>OS:</strong> {{ $device->os_short ?? '—' }} <br>
                                <strong>Chipset:</strong> {{ $device->chipset_short ?? '—' }} <br>
                                <strong>Storage:</strong> {{ $device->storage_short ?? '—' }} <br>
                                <strong>Camera:</strong> {{ $device->main_camera_short ?? '—' }} <br>
                                <strong>RAM:</strong> {{ $device->ram_short ?? '—' }}
                            </td>

                            <!-- Status -->
                            <td>
                                @if($device->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-danger">Unpublished</span>
                                @endif
                            </td>

                            <!-- Created -->
                            <td>{{ $device->created_at->format('M d, Y') }}</td>

                            <!-- Actions -->
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.devices.edit', $device->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.devices.destroy', $device->id) }}" method="POST" class="d-inline">
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
                            <td colspan="7" class="text-center">
                                No devices found.
                                <a href="{{ route('admin.devices.create') }}">Create the first device</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        let table1 = document.querySelector('#table1');
        new simpleDatatables.DataTable(table1);
    </script>
@endpush

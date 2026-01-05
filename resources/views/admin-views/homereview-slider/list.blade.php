@extends('admin-views.layouts.admin')
@section('title', 'Home Review Slider List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Home Review Sliders</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Home Review Sliders</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Home Review Sliders List</h4>
                <a href="{{ route('admin.homereview-slider.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New Slider
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Review Link</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sliders as $slider)
                            <tr>
                                <td>
                                    @if($slider->image)
                                        <img src="{{ asset('storage/' . $slider->image) }}" alt="Slider Image" class="rounded"
                                            width="100" height="60" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                            style="width: 100px; height: 60px;">
                                            <i class="bi bi-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><a href="{{ $slider->review_link }}" target="_blank">{{ $slider->review_link }}</a></td>
                                <td>{{ $slider->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.homereview-slider.edit', $slider->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.homereview-slider.destroy', $slider->id) }}" method="POST"
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
                                <td colspan="4" class="text-center">No sliders found. <a
                                        href="{{ route('admin.homereview-slider.create') }}">Create the first slider</a></td>
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
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
@endpush
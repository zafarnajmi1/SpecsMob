@extends('admin-views.layouts.admin')
@section('title', 'Videos List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>All Videos</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Videos</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Videos List</h4>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Video
            </a>
        </div>

        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>YouTube ID</th>
                        <th>Brand</th>
                        <th>Device</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($videos as $video)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            
                            <!-- Thumbnail -->
                            <td>
                                @if($video->youtube_id)
                                    <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" 
                                         alt="{{ $video->title }}"
                                         width="80" height="60"
                                         style="object-fit: cover; border-radius: 8px;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                         style="width:80px; height:60px; border-radius:8px;">
                                        <i class="bi bi-play-circle"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Title -->
                            <td>
                                <div>
                                    <strong>{{ Str::limit($video->title, 40) }}</strong>
                                    @if($video->slug)
                                        <br>
                                        <small class="text-muted">{{ $video->slug }}</small>
                                    @endif
                                </div>
                            </td>

                            <!-- YouTube ID -->
                            <td>
                                @if($video->youtube_id)
                                    <code>{{ $video->youtube_id }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Brand -->
                            <td>
                                @if($video->brands->count() > 0)
                                    @foreach($video->brands as $brand)
                                        <span class="badge bg-primary">{{ $brand->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Device -->
                            <td>
                                @if($video->devices->count() > 0)
                                    @foreach($video->devices as $device)
                                        <span class="badge bg-success">{{ $device->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if($video->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>

                            <!-- Views -->
                            <td>
                                {{ number_format($video->views_count) }}
                            </td>

                            <!-- Created At -->
                            <td>{{ $video->created_at->format('M d, Y') }}</td>

                            <!-- Actions -->
                            <td>
                                <div class="btn-group">
                                    <!-- Edit -->
                                    <a href="{{ route('admin.videos.edit', $video->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Video">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- View on YouTube -->
                                    @if($video->youtube_id)
                                        <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" 
                                           target="_blank"
                                           class="btn btn-sm btn-outline-success"
                                           title="View on YouTube">
                                            <i class="bi bi-youtube"></i>
                                        </a>
                                    @endif

                                    <!-- Delete -->
                                    <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger confirm-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="bi bi-play-circle display-4 text-muted"></i>
                                <p class="mt-2 mb-0">No videos found</p>
                                <p class="text-muted">Get started by creating your first video</p>
                                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-1"></i> Create Video
                                </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Simple Datatable
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);

            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.confirm-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (confirm('Are you sure you want to delete this video?')) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
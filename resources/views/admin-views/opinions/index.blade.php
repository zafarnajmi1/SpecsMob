@extends('admin-views.layouts.admin')

@section('title', 'User Opinions Moderation')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>User Opinions</h3>
                    <p class="text-subtitle text-muted">Moderate and manage user opinions on devices.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Opinions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <form action="{{ route('admin.opinions.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search opinions..."
                                value="{{ request('search') }}">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Device</th>
                                    <th>Opinion</th>
                                    <th>Stats</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opinions as $opinion)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-2 flex-shrink-0"
                                                    style="width: 2.5rem; height: 2.5rem;">
                                                    {{ strtoupper(substr($opinion->user->name ?? 'G', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $opinion->user->name ?? 'Guest' }}</p>
                                                    <small
                                                        class="text-muted">{{ $opinion->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light-info text-dark">{{ $opinion->device->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($opinion->title)
                                                <div class="fw-bold mb-1">{{ $opinion->title }}</div>
                                            @endif
                                            <div class="text-wrap" style="max-width: 300px;">
                                                {{ Str::limit($opinion->body, 150) }}
                                            </div>
                                            @if($opinion->rating)
                                                <div class="mt-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bi bi-star{{ $i <= ($opinion->rating / 2) ? '-fill text-warning' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <small><i class="bi bi-hand-thumbs-up"></i> {{ $opinion->likes_count }}
                                                    Likes</small>
                                                <small><i class="bi bi-chat"></i>
                                                    {{ $opinion->replies_count ?? $opinion->replies()->count() }}
                                                    Replies</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($opinion->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if(!$opinion->is_approved)
                                                    <form action="{{ route('admin.opinions.approve', $opinion->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            title="Approve">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.opinions.reject', $opinion->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                            title="Move to Pending">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.opinions.destroy', $opinion->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No opinions found matching your criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $opinions->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
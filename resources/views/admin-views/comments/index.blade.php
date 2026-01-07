@extends('admin-views.layouts.admin')

@section('title', 'Comments Moderation')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>General Comments</h3>
                    <p class="text-subtitle text-muted">Moderate polymorphic comments across Reviews and Devices.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">General Comments</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header border-bottom mb-3">
                    <form action="{{ route('admin.comments.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search comment text..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Comments</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>On Content</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td style="width: 200px;">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <p class="mb-0 fw-bold">{{ $comment->user->name ?? 'Guest User' }}</p>
                                                    <small
                                                        class="text-muted text-xs">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $type = class_basename($comment->commentable_type);
                                                $color = match ($type) {
                                                    'Review' => 'primary',
                                                    'Device' => 'info',
                                                    'DeviceOpinion' => 'secondary',
                                                    default => 'dark'
                                                };
                                            @endphp
                                            <span class="badge bg-light-{{ $color }} text-{{ $color }} mb-1">{{ $type }}</span>
                                            <div class="text-xs text-muted fw-bold">ID: #{{ $comment->commentable_id }}</div>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="min-width: 250px;">
                                                {{ $comment->body }}
                                            </div>
                                            @if($comment->parent_id)
                                                <div class="mt-2">
                                                    <small class="text-primary bg-light px-2 py-1 rounded">
                                                        <i class="bi bi-reply-fill"></i> Reply to ID #{{ $comment->parent_id }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($comment->is_approved)
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if(!$comment->is_approved)
                                                    <form action="{{ route('admin.comments.approve', $comment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.comments.reject', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                                            <i class="bi bi-pause-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                                    onsubmit="return confirm('Immediately delete this comment? This cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-chat-dots fs-1 text-muted"></i>
                                            <p class="mt-2">All caught up! No comments found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
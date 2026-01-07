@extends('admin-views.layouts.admin')

@section('title', 'Article Comments Moderation')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Article Comments</h3>
                    <p class="text-subtitle text-muted">Manage discussions on your news and blog posts.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Article Comments</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('admin.article-comments.index') }}" method="GET"
                        class="d-flex gap-2 justify-content-end">
                        <input type="text" name="search" class="form-control w-25" placeholder="Filter by text..."
                            value="{{ request('search') }}">
                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="">Status: All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                        <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-lg">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Article</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td class="text-nowrap">
                                            <div class="fw-bold">{{ $comment->user->name ?? 'User' }}</div>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 200px;">
                                                <a href="{{ route('admin.articles.edit', $comment->article_id) }}"
                                                    class="text-primary fw-medium text-xs">
                                                    {{ $comment->article->title ?? 'Deleted Article' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="p-2 bg-light border-start border-4 border-primary rounded-end">
                                                {{ $comment->body }}
                                            </div>
                                            <div class="mt-1 d-flex gap-3 text-xs">
                                                <span><i class="bi bi-heart text-danger"></i> {{ $comment->likes_count }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($comment->is_approved)
                                                <span class="badge bg-light-success text-success fw-bolder">LIVE</span>
                                            @else
                                                <span class="badge bg-light-warning text-warning fw-bolder">HOLD</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if(!$comment->is_approved)
                                                    <form action="{{ route('admin.article-comments.approve', $comment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-icon btn-sm btn-success"
                                                            title="Approve">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.article-comments.destroy', $comment->id) }}"
                                                    method="POST" onsubmit="return confirm('Delete this article comment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No comments found in this section.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@extends('admin-views.layouts.admin')
@section('title', 'Articles Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
    <style>
        .form-control,
        .form-select {
            color: #000 !important;
        }

        .form-control::placeholder {
            color: #6c757d !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Articles Management</h3>
                    <p class="text-subtitle text-muted">Manage news, blog posts, and featured content</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Articles</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        All Articles
                        @if(request()->has('type'))
                            <span class="badge bg-primary ms-2">
                                {{ ucfirst(request('type')) }}
                            </span>
                        @endif
                    </h4>
                    @can('article_create')
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add New Article
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>News</option>
                                <option value="article" {{ request('type') == 'article' ? 'selected' : '' }}>Blog Posts
                                </option>
                                <option value="featured" {{ request('type') == 'featured' ? 'selected' : '' }}>Featured
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search articles..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Articles Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="articlesTable">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Views</th>
                                    <th>Published Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                    <tr>
                                        <td>
                                            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="rounded"
                                                width="60" height="60" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ Str::limit($article->title, 50) }}</h6>
                                                <small class="text-muted">{{ $article->slug }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                            @if($article->type == 'news') bg-info
                                                            @elseif($article->type == 'article') bg-success
                                                            @elseif($article->type == 'featured') bg-warning
                                                            @endif">
                                                {{ ucfirst($article->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $article->author->name ?? 'System' }}</td>
                                        <td>
                                            @if(!$article->is_published)
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($article->published_at && $article->published_at > now())
                                                <span class="badge bg-warning">Scheduled</span>
                                            @else
                                                <span class="badge bg-success">Published</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($article->is_featured)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        </td>
                                        <td>{{ number_format($article->views_count) }}</td>
                                        <td>
                                            @if($article->published_at)
                                                {{ $article->published_at->format('M j, Y') }}
                                            @else
                                                <span class="text-muted">No date</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @can('article_edit')
                                                    <a href="{{ route('admin.articles.edit', $article->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan

                                                @can('article_delete')
                                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
                                                        class="d-inline" id="delete-form-{{ $article->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger confirm-delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="bi bi-newspaper display-4 text-muted"></i>
                                            <p class="mt-2 mb-0">No articles found</p>
                                            <p class="text-muted">Get started by creating your first article</p>
                                            @can('article_create')
                                                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary mt-2">
                                                    <i class="bi bi-plus-circle me-1"></i> Create Article
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Simple DataTable
            let table1 = document.querySelector('#articlesTable');
            let dataTable = new simpleDatatables.DataTable(table1, {
                searchable: true,
                fixedHeight: false,
                perPage: 25,
                perPageSelect: [10, 25, 50, 100]
            });

            // Filter by type
            const typeFilter = document.getElementById('typeFilter');
            if (typeFilter) {
                typeFilter.addEventListener('change', function () {
                    const type = this.value;
                    const url = new URL(window.location.href);

                    if (type) {
                        url.searchParams.set('type', type);
                    } else {
                        url.searchParams.delete('type');
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }

            // Status filter functionality
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function () {
                    const status = this.value;
                    const url = new URL(window.location.href);

                    if (status) {
                        url.searchParams.set('status', status);
                    } else {
                        url.searchParams.delete('status');
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }

            // Search functionality integrated with DataTable
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            if (searchInput && searchButton) {
                searchButton.addEventListener('click', function () {
                    dataTable.search(searchInput.value);
                });

                searchInput.addEventListener('keypress', function (e) {
                    if (e.which === 13) {
                        dataTable.search(this.value);
                    }
                });
            }



            // Add custom styling for DataTable
            const style = document.createElement('style');
            style.textContent = `
                        .datatable-wrapper .datatable-container {
                            font-size: 0.875rem;
                        }
                        .datatable-top {
                            padding: 1rem 0;
                        }
                        .datatable-search input {
                            border: 1px solid #dee2e6;
                            border-radius: 0.375rem;
                            padding: 0.375rem 0.75rem;
                        }
                        .datatable-selector {
                            border: 1px solid #dee2e6;
                            border-radius: 0.375rem;
                            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
                        }
                        .datatable-pagination-list {
                            flex-wrap: wrap;
                        }
                        .datatable-pagination-list-item {
                            margin: 0.125rem;
                        }
                        .datatable-pagination-list-item .datatable-pagination-list-item-link {
                            border: 1px solid #dee2e6;
                            border-radius: 0.375rem;
                            padding: 0.375rem 0.75rem;
                        }
                        .datatable-pagination-list-item.active .datatable-pagination-list-item-link {
                            background-color: #0d6efd;
                            border-color: #0d6efd;
                            color: white;
                        }
                    `;
            document.head.appendChild(style);
        });
    </script>
@endpush
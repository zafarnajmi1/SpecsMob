@extends('admin-views.layouts.admin')

@section('title', 'Deals & Offers Management')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Deals & Offers</h3>
                    <p class="text-subtitle text-muted">Manage phone deals and special offers.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Deals & Offers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">All Deals</h5>
                    </div>
                    <a href="{{ route('admin.deals.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Deal
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="mb-4">
                        <form action="{{ route('admin.deals.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search deals..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="region" class="form-select">
                                    <option value="">All Regions</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region }}"
                                            {{ request('region') == $region ? 'selected' : '' }}>
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Device</th>
                                    <th>Store</th>
                                    <th>Price Info</th>
                                    <th>Memory</th>
                                    <th>Region</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deals as $deal)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $deal->title }}</div>
                                            <small class="text-muted d-block">{{ $deal->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            @if($deal->device)
                                                <span class="badge bg-light-info text-dark">
                                                    {{ $deal->device->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-light-secondary">Generic</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $deal->store_name }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">{{ $deal->price }}</div>
                                            @if($deal->original_price)
                                                <small class="text-decoration-line-through text-muted">
                                                    {{ $deal->original_price }}
                                                </small>
                                            @endif
                                            @if($deal->discount_percent)
                                                <div class="badge bg-warning text-dark">
                                                    {{ $deal->discount_percent }} OFF
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $deal->memory ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-dark">
                                                {{ $deal->region }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($deal->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.deals.edit', $deal->id) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this deal?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ $deal->link }}" target="_blank" class="btn btn-sm btn-outline-info"
                                                    title="View on Store">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mt-2">No deals found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $deals->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

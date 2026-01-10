@extends('admin-views.layouts.admin')
@section('title', 'Messages & Enquiries')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Messages & Enquiries</h3>
                    <p class="text-subtitle text-muted">View and manage contact form and tip submissions</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Messages</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title">All Messages</h4>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4 pt-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter by Type</label>
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="Contact">General Contact</option>
                                <option value="Tip">Hot Tips</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter by Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="Unread">Unread</option>
                                <option value="Read">Read</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="messagesTable">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>From</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                    <tr class="{{ !$message->is_read ? 'table-light fw-bold' : '' }}">
                                        <td>
                                            @if(!$message->is_read)
                                                <span class="badge bg-danger">Unread</span>
                                            @else
                                                <span class="badge bg-secondary">Read</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->type == 'contact')
                                                <span class="text-primary"><i class="bi bi-envelope-fill me-1"></i> Contact</span>
                                            @else
                                                <span class="text-warning"><i class="bi bi-lightning-fill me-1"></i> Tip</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($message->type == 'contact')
                                                <div>
                                                    <div class="mb-0">{{ $message->name }}</div>
                                                    <small class="text-muted">{{ $message->email }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Anonymous Tip</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;">
                                                {{ $message->subject ?? '(No Subject)' }}
                                            </div>
                                        </td>
                                        <td>{{ $message->created_at->format('M j, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.messages.show', $message->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST"
                                                    class="d-inline ms-1 shadow-none confirm-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            const messagesTable = document.getElementById('messagesTable');
            const dataTable = new simpleDatatables.DataTable(messagesTable, {
                searchable: true,
                fixedHeight: false,
                perPage: 25
            });

            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');

            function applyFilters() {
                const typeValue = typeFilter.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();

                // Simple-DataTables search is global, but we can try to filter rows manually
                // or just use the search API if it supports columns. 
                // Alternatively, we can just use the search() method with a combined string
                // but that's messy. Let's filter the data source or use the search API.

                // For Simple-DataTables, the easiest way to handle custom filters is to 
                // just use the global search or custom column filtering if available.
                // However, the vanilla search() is global.

                // Let's use the search() with the combined terms or just one term
                // Since user wants "searching etc instead of laravel route calling",
                // they might just want the general search bar too.

                if (typeValue || statusValue) {
                    dataTable.search(typeValue + " " + statusValue);
                } else {
                    dataTable.search("");
                }
            }

            typeFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);

            // Re-register delete confirmation for new rows if table re-renders 
            // but Simple-DataTables usually preserves buttons or needs re-binding.
            dataTable.on('datatable.page', () => {
                if (typeof registerDeleteConfirmation === 'function') registerDeleteConfirmation();
            });
            dataTable.on('datatable.search', () => {
                if (typeof registerDeleteConfirmation === 'function') registerDeleteConfirmation();
            });
        });
    </script>
@endpush
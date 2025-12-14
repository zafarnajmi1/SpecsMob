@extends('admin-views.layouts.admin')
@section('title', 'Currencies List')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/simple-datatables/style.css') }}">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>All Currencies</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Currencies</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Currencies List</h4>

                {{-- CREATE button (opens modal in "create" mode) --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#currencyModal"
                    data-mode="create">
                    <i class="bi bi-plus"></i> Add New Currency
                </button>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ISO Code</th>
                            <th>Symbol</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currencies as $currency)
                            <tr>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->iso_code }}</td>
                                <td>{{ $currency->symbol }}</td>
                                <td>{{ $currency->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group">

                                        {{-- EDIT button (opens same modal in "edit" mode) --}}
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#currencyModal" data-mode="edit" data-id="{{ $currency->id }}"
                                            data-name="{{ $currency->name }}" data-iso-code="{{ $currency->iso_code }}"
                                            data-symbol="{{ $currency->symbol }}"
                                            data-update-url="{{ route('admin.currencies.update', $currency->id) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.currencies.destroy', $currency->id) }}" method="POST"
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
                                    No currencies found.
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#currencyModal"
                                        data-mode="create">
                                        Create the first currency
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
    <div class="modal fade text-left" id="currencyModal" tabindex="-1" role="dialog"
        aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form id="currencyForm" method="POST">
                    @csrf
                    {{-- _method will be changed via JS (POST / PUT) --}}
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="currencyModalLabel">Add New Currency</h5>
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
                                        <label for="currencyName">Name</label>
                                        <input type="text" id="currencyName"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            placeholder="Currency name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- ISO Code --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="currencyIsoCode">ISO Code</label>
                                        <input type="text" id="currencyIsoCode"
                                            class="form-control @error('iso_code') is-invalid @enderror" name="iso_code"
                                            placeholder="3-letter code (e.g., USD, EUR)" value="{{ old('iso_code') }}"
                                            maxlength="3" style="text-transform: uppercase;">
                                        @error('iso_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Symbol --}}
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="currencySymbol">Symbol</label>
                                        <input type="text" id="currencySymbol"
                                            class="form-control @error('symbol') is-invalid @enderror" name="symbol"
                                            placeholder="Currency symbol (e.g., $, €, £)" value="{{ old('symbol') }}"
                                            maxlength="8">
                                        @error('symbol')
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
        const currencyModal = document.getElementById('currencyModal');

        if (currencyModal) {
            currencyModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const mode = button.getAttribute('data-mode') || 'create';

                const form = document.getElementById('currencyForm');
                const methodInput = document.getElementById('formMethod');
                const nameInput = document.getElementById('currencyName');
                const isoCodeInput = document.getElementById('currencyIsoCode');
                const symbolInput = document.getElementById('currencySymbol');
                const title = document.getElementById('currencyModalLabel');

                if (mode === 'edit') {
                    const updateUrl = button.getAttribute('data-update-url');
                    const name = button.getAttribute('data-name');
                    const isoCode = button.getAttribute('data-iso-code');
                    const symbol = button.getAttribute('data-symbol');

                    form.action = updateUrl;
                    methodInput.value = 'PUT';
                    title.textContent = 'Edit Currency';

                    nameInput.value = name || '';
                    isoCodeInput.value = isoCode || '';
                    symbolInput.value = symbol || '';
                } else {
                    // CREATE mode
                    form.action = "{{ route('admin.currencies.store') }}";
                    methodInput.value = 'POST';
                    title.textContent = 'Add New Currency';

                    nameInput.value = '';
                    isoCodeInput.value = '';
                    symbolInput.value = '';
                }
            });
        }

        // Auto-uppercase ISO code
        document.getElementById('currencyIsoCode')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    </script>
@endpush
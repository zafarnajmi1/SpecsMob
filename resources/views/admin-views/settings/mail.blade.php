@extends('admin-views.layouts.admin')

@section('title', 'Mail Configuration')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Mail Configuration</h3>
                    <p class="text-subtitle text-muted">Configure SMTP settings for system emails.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mail Configuration</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header border-bottom mb-3">
                            <h4 class="card-title"><i class="bi bi-envelope-at me-2 text-primary"></i>SMTP Server Details
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Mail Host</label>
                                            <input type="text" name="mail_host" class="form-control"
                                                value="{{ $settings->mail_host }}" placeholder="smtp.mailtrap.io">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Mail Port</label>
                                            <input type="text" name="mail_port" class="form-control"
                                                value="{{ $settings->mail_port }}" placeholder="587">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="mail_username" class="form-control"
                                                value="{{ $settings->mail_username }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="mail_password" class="form-control"
                                                value="{{ $settings->mail_password }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Encryption</label>
                                            <select name="mail_encryption" class="form-select">
                                                <option value="tls" {{ $settings->mail_encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                                <option value="ssl" {{ $settings->mail_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                <option value="" {{ $settings->mail_encryption == '' ? 'selected' : '' }}>None
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h5 class="mb-3">Sender Information</h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">From Address</label>
                                            <input type="email" name="mail_from_address" class="form-control"
                                                value="{{ $settings->mail_from_address }}"
                                                placeholder="no-reply@gsmarena-clone.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">From Name</label>
                                            <input type="text" name="mail_from_name" class="form-control"
                                                value="{{ $settings->mail_from_name }}" placeholder="GSMArena Clone">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary">Test Connection</button>
                                    <button type="submit" class="btn btn-primary px-4">Save Mail Configuration</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
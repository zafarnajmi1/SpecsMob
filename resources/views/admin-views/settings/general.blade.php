@extends('admin-views.layouts.admin')

@section('title', 'General Settings')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>General Settings</h3>
                    <p class="text-subtitle text-muted">Configure your site's identity and contact information.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">General Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" name="site_name" id="site_name" class="form-control"
                                        value="{{ $settings->site_name }}" placeholder="e.g. GSMArena Clone">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="contact_email" class="form-label">Contact Email</label>
                                            <input type="email" name="contact_email" id="contact_email" class="form-control"
                                                value="{{ $settings->contact_email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                                                value="{{ $settings->contact_phone }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Office Address</label>
                                    <textarea name="address" id="address" class="form-control"
                                        rows="3">{{ $settings->address }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="footer_text" class="form-label">Footer Copyright Text</label>
                                    <input type="text" name="footer_text" id="footer_text" class="form-control"
                                        value="{{ $settings->footer_text }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Branding</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label class="form-label">Site Logo</label>
                                    <div class="mb-3">
                                        @if($settings->site_logo)
                                            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo"
                                                class="img-fluid rounded border p-2 mb-2" style="max-height: 80px;">
                                        @endif
                                        <input type="file" name="site_logo" class="form-control">
                                    </div>
                                    <small class="text-muted">Recommended size: 200x50px (PNG/SVG)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Favicon</label>
                                    <div class="mb-3">
                                        @if($settings->site_favicon)
                                            <img src="{{ asset('storage/' . $settings->site_favicon) }}" alt="Favicon"
                                                class="rounded border p-2 mb-2" style="width: 32px; height: 32px;">
                                        @endif
                                        <input type="file" name="site_favicon" class="form-control">
                                    </div>
                                    <small class="text-muted">Recommended size: 32x32px (ICO/PNG)</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
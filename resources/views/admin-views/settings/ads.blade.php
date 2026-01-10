@extends('admin-views.layouts.admin')

@section('title', 'Ad Management')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ad Management</h3>
                    <p class="text-subtitle text-muted">Inject advertisement scripts into various site positions.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ad Management</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> Note: You can paste HTML/JavaScript ad scripts here. They will be
                rendered directly in the corresponding positions.
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title text-primary">Header Banner (728x90)</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="header_ad_script" class="form-control font-monospace" rows="8"
                                    placeholder="<!-- Paste script here -->">{{ $settings->header_ad_script }}</textarea>
                                <p class="text-muted mt-2 small">Displays at the top of the page under the navbar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title text-success">Sidebar Sticky Ad (300x600)</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="sidebar_ad_script" class="form-control font-monospace" rows="8"
                                    placeholder="<!-- Paste script here -->">{{ $settings->sidebar_ad_script }}</textarea>
                                <p class="text-muted mt-2 small">Displays in the sidebar on device and article pages.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title text-info">Article Content Ad</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="article_middle_ad_script" class="form-control font-monospace" rows="8"
                                    placeholder="<!-- Paste script here -->">{{ $settings->article_middle_ad_script }}</textarea>
                                <p class="text-muted mt-2 small">Displays in the middle of long-form articles or news.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title text-dark">Footer Banner</h4>
                            </div>
                            <div class="card-body">
                                <textarea name="footer_ad_script" class="form-control font-monospace" rows="8"
                                    placeholder="<!-- Paste script here -->">{{ $settings->footer_ad_script }}</textarea>
                                <p class="text-muted mt-2 small">Displays just above the site footer link area.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 pb-5 sticky-bottom bg-white p-3 border-top shadow-sm d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="bi bi-shield-check me-1"></i> Data is encrypted and safe.</span>
                    <button type="submit" class="btn btn-primary px-5 btn-lg">Deploy Ad Scripts</button>
                </div>
            </form>
        </section>
    </div>
@endsection
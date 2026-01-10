@extends('admin-views.layouts.admin')

@section('title', 'Social Media Links')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Social Media Links</h3>
                    <p class="text-subtitle text-muted">Manage your platform's social presence.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Social Links</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Social Profiles</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label"><i class="bi bi-facebook text-primary me-2"></i> Facebook
                                        URL</label>
                                    <input type="url" name="facebook_url" class="form-control"
                                        value="{{ $settings->facebook_url }}" placeholder="https://facebook.com/yourpage">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label"><i class="bi bi-twitter text-info me-2"></i> Twitter / X
                                        URL</label>
                                    <input type="url" name="twitter_url" class="form-control"
                                        value="{{ $settings->twitter_url }}" placeholder="https://twitter.com/yourprofile">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label"><i class="bi bi-instagram text-danger me-2"></i> Instagram
                                        URL</label>
                                    <input type="url" name="instagram_url" class="form-control"
                                        value="{{ $settings->instagram_url }}"
                                        placeholder="https://instagram.com/yourprofile">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label"><i class="bi bi-youtube text-danger me-2"></i> YouTube Channel
                                        URL</label>
                                    <input type="url" name="youtube_url" class="form-control"
                                        value="{{ $settings->youtube_url }}"
                                        placeholder="https://youtube.com/c/yourchannel">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label"><i class="bi bi-linkedin text-primary me-2"></i> LinkedIn
                                        URL</label>
                                    <input type="url" name="linkedin_url" class="form-control"
                                        value="{{ $settings->linkedin_url }}"
                                        placeholder="https://linkedin.com/company/yourpage">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5">Save Social Links</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
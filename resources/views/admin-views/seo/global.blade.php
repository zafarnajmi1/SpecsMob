@extends('admin-views.layouts.admin')
@section('title', 'Global SEO Settings')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Global SEO Settings</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Global SEO Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.seo.global.save') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">SEO Configuration</h4>
                            </div>
                            <div class="card-body">

                                {{-- Section 1: Basic Meta --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-info-circle me-2"></i>Basic Meta Tags
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="site_name" class="form-label">Site Name</label>
                                                            <input type="text" id="site_name"
                                                                   class="form-control @error('site_name') is-invalid @enderror"
                                                                   name="site_name"
                                                                   value="{{ old('site_name', $settings->site_name ?? '') }}"
                                                                   placeholder="Specsmob, GSMArena Clone, etc.">
                                                            @error('site_name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="default_meta_title" class="form-label">
                                                                Default Meta Title
                                                            </label>
                                                            <input type="text" id="default_meta_title"
                                                                   class="form-control @error('default_meta_title') is-invalid @enderror"
                                                                   name="default_meta_title"
                                                                   value="{{ old('default_meta_title', $settings->default_meta_title ?? '') }}"
                                                                   placeholder="Best Mobile Specs, Reviews and Prices">
                                                            @error('default_meta_title')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="default_meta_description" class="form-label">
                                                                Default Meta Description
                                                            </label>
                                                            <textarea id="default_meta_description"
                                                                      class="form-control @error('default_meta_description') is-invalid @enderror"
                                                                      name="default_meta_description"
                                                                      rows="3"
                                                                      placeholder="150–160 characters summary for pages without a custom description.">{{ old('default_meta_description', $settings->default_meta_description ?? '') }}</textarea>
                                                            @error('default_meta_description')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="default_meta_keywords" class="form-label">
                                                                Default Keywords (optional)
                                                            </label>
                                                            <input type="text" id="default_meta_keywords"
                                                                   class="form-control @error('default_meta_keywords') is-invalid @enderror"
                                                                   name="default_meta_keywords"
                                                                   value="{{ old('default_meta_keywords', $settings->default_meta_keywords ?? '') }}"
                                                                   placeholder="mobile review, specs, price in Pakistan">
                                                            @error('default_meta_keywords')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">Used for older search engines only.</small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="robots_default" class="form-label">Robots Default</label>
                                                            <select id="robots_default"
                                                                    class="form-select @error('robots_default') is-invalid @enderror"
                                                                    name="robots_default">
                                                                @php
                                                                    $robots = old('robots_default', $settings->robots_default ?? 'index,follow');
                                                                @endphp
                                                                <option value="index,follow" {{ $robots === 'index,follow' ? 'selected' : '' }}>index, follow</option>
                                                                <option value="noindex,nofollow" {{ $robots === 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                                                                <option value="noindex,follow" {{ $robots === 'noindex,follow' ? 'selected' : '' }}>noindex, follow</option>
                                                                <option value="index,nofollow" {{ $robots === 'index,nofollow' ? 'selected' : '' }}>index, nofollow</option>
                                                            </select>
                                                            @error('robots_default')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-12">
                                                        <div class="form-group">
                                                            <label for="canonical_base_url" class="form-label">
                                                                Canonical Base URL
                                                            </label>
                                                            <input type="text" id="canonical_base_url"
                                                                   class="form-control @error('canonical_base_url') is-invalid @enderror"
                                                                   name="canonical_base_url"
                                                                   value="{{ old('canonical_base_url', $settings->canonical_base_url ?? config('app.url')) }}"
                                                                   placeholder="https://specsmob.com">
                                                            @error('canonical_base_url')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <small class="text-muted">
                                                                Used to generate canonical tags if page-specific URL is not set.
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section 2: Social (OG & Twitter) --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-share-fill me-2"></i>Social Sharing (Open Graph & Twitter)
                                                </h5>
                                            </div>
                                            <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="og_site_name" class="form-label">OG Site Name</label>
                        <input type="text" id="og_site_name"
                               class="form-control @error('og_site_name') is-invalid @enderror"
                               name="og_site_name"
                               value="{{ old('og_site_name', $settings->og_site_name ?? ($settings->site_name ?? '')) }}">
                        @error('og_site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="og_default_title" class="form-label">Default OG Title</label>
                        <input type="text" id="og_default_title"
                               class="form-control @error('og_default_title') is-invalid @enderror"
                               name="og_default_title"
                               value="{{ old('og_default_title', $settings->og_default_title ?? '') }}">
                        @error('og_default_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="og_default_description" class="form-label">Default OG Description</label>
                        <textarea id="og_default_description"
                                  class="form-control @error('og_default_description') is-invalid @enderror"
                                  name="og_default_description"
                                  rows="2">{{ old('og_default_description', $settings->og_default_description ?? '') }}</textarea>
                        @error('og_default_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="og_default_image" class="form-label">Default OG Image URL</label>
                        <input type="text" id="og_default_image"
                               class="form-control @error('og_default_image') is-invalid @enderror"
                               name="og_default_image"
                               value="{{ old('og_default_image', $settings->og_default_image ?? '') }}"
                               placeholder="https://specsmob.com/images/og-default.jpg">
                        @error('og_default_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Used when page has no custom OG image.</small>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="twitter_default_image" class="form-label">Default Twitter Image URL</label>
                        <input type="text" id="twitter_default_image"
                               class="form-control @error('twitter_default_image') is-invalid @enderror"
                               name="twitter_default_image"
                               value="{{ old('twitter_default_image', $settings->twitter_default_image ?? '') }}"
                               placeholder="https://specsmob.com/images/twitter-default.jpg">
                        @error('twitter_default_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="twitter_default_title" class="form-label">Default Twitter Title</label>
                        <input type="text" id="twitter_default_title"
                               class="form-control @error('twitter_default_title') is-invalid @enderror"
                               name="twitter_default_title"
                               value="{{ old('twitter_default_title', $settings->twitter_default_title ?? '') }}">
                        @error('twitter_default_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <label for="twitter_default_description" class="form-label">Default Twitter Description</label>
                        <textarea id="twitter_default_description"
                                  class="form-control @error('twitter_default_description') is-invalid @enderror"
                                  name="twitter_default_description"
                                  rows="2">{{ old('twitter_default_description', $settings->twitter_default_description ?? '') }}</textarea>
                        @error('twitter_default_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-check-lg"></i> Save SEO Settings
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light-secondary">
                                        <i class="bi bi-arrow-left"></i> Cancel
                                    </a>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

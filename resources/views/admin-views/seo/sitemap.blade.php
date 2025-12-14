@extends('admin-views.layouts.admin')
@section('title', 'Sitemap & Robots')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Sitemap & Robots</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sitemap & Robots</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.seo.sitemap.save') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sitemap & Robots Configuration</h4>
                            </div>
                            <div class="card-body">

                                {{-- Sitemap --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-diagram-3 me-2"></i>Sitemap
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="sitemap_url" class="form-label">Sitemap URL</label>
                                                    <input type="text" id="sitemap_url"
                                                           class="form-control @error('sitemap_url') is-invalid @enderror"
                                                           name="sitemap_url"
                                                           value="{{ old('sitemap_url', $sitemapSettings->sitemap_url ?? url('/sitemap.xml')) }}">
                                                    @error('sitemap_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">
                                                        This will be referenced in robots.txt and optionally via a &lt;link rel="sitemap"&gt; tag.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Robots.txt --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-robot me-2"></i>Robots.txt
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="robots_content" class="form-label">robots.txt Content</label>
                                                    <textarea id="robots_content"
                                                              class="form-control @error('robots_content') is-invalid @enderror"
                                                              name="robots_content"
                                                              rows="8">{{ old('robots_content', $sitemapSettings->robots_content ?? "User-agent: *\nDisallow:\nSitemap: " . (url('/sitemap.xml'))) }}</textarea>
                                                    @error('robots_content')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">
                                                        This can be stored and written to public/robots.txt by a command or deployment script.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hreflang --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-translate me-2"></i>Hreflang Configuration
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted mb-2">
                                                    Configure alternate language URLs (e.g., EN global, PK, IN).
                                                </p>
                                                <div class="row">
                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="hreflang_en" class="form-label">Default (en)</label>
                                                            <input type="text" id="hreflang_en"
                                                                   class="form-control @error('hreflang_en') is-invalid @enderror"
                                                                   name="hreflang_en"
                                                                   value="{{ old('hreflang_en', $sitemapSettings->hreflang_en ?? url('/')) }}"
                                                                   placeholder="https://specsmob.com">
                                                            @error('hreflang_en')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="hreflang_en_pk" class="form-label">Pakistan (en-PK)</label>
                                                            <input type="text" id="hreflang_en_pk"
                                                                   class="form-control @error('hreflang_en_pk') is-invalid @enderror"
                                                                   name="hreflang_en_pk"
                                                                   value="{{ old('hreflang_en_pk', $sitemapSettings->hreflang_en_pk ?? url('/pk')) }}"
                                                                   placeholder="https://specsmob.com/pk">
                                                            @error('hreflang_en_pk')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-12">
                                                        <div class="form-group">
                                                            <label for="hreflang_en_in" class="form-label">India (en-IN)</label>
                                                            <input type="text" id="hreflang_en_in"
                                                                   class="form-control @error('hreflang_en_in') is-invalid @enderror"
                                                                   name="hreflang_en_in"
                                                                   value="{{ old('hreflang_en_in', $sitemapSettings->hreflang_en_in ?? url('/in')) }}"
                                                                   placeholder="https://specsmob.com/in">
                                                            @error('hreflang_en_in')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    Your layout can use these to output &lt;link rel="alternate" hreflang="..."&gt; tags.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-check-lg"></i> Save Sitemap & Robots
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

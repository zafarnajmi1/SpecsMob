@extends('admin-views.layouts.admin')
@section('title', 'Schema & Structured Data')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Schema & Structured Data</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Schema & Structured Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.seo.schema.save') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Schema Configuration</h4>
                            </div>
                            <div class="card-body">

                                {{-- Organization Schema --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-building me-2"></i>Organization Schema
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted mb-2">
                                                    Used for your site-wide Organization / Brand schema.
                                                </p>
                                                <div class="form-group">
                                                    <label for="organization_schema" class="form-label">
                                                        JSON-LD (Organization)
                                                    </label>
                                                    <textarea id="organization_schema"
                                                              class="form-control @error('organization_schema') is-invalid @enderror"
                                                              name="organization_schema"
                                                              rows="10"
                                                              placeholder='{"context": "https://schema.org", "@type": "Organization", ...}'>{{ old('organization_schema', $schemaSettings->organization_schema ?? '') }}</textarea>
                                                    @error('organization_schema')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">
                                                        Paste valid JSON-LD. This will be injected on all pages where needed.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Default Article Schema --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-file-earmark-text me-2"></i>Default Article Schema
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted mb-2">
                                                    Template used for news, blogs, featured content. You can use placeholders like
                                                    <code>{{ '{title}' }}</code>, <code>{{ '{url}' }}</code>, <code>{{ '{image}' }}</code>, <code>{{ '{published_at}' }}</code>.
                                                </p>
                                                <div class="form-group">
                                                    <label for="article_schema_template" class="form-label">
                                                        JSON-LD (Article Template)
                                                    </label>
                                                    <textarea id="article_schema_template"
                                                              class="form-control @error('article_schema_template') is-invalid @enderror"
                                                              name="article_schema_template"
                                                              rows="10">{{ old('article_schema_template', $schemaSettings->article_schema_template ?? '') }}</textarea>
                                                    @error('article_schema_template')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Default Product Schema (for devices) --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light-primary">
                                                <h5 class="card-title text-primary mb-0">
                                                    <i class="bi bi-phone me-2"></i>Default Product Schema (Devices)
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted mb-2">
                                                    Template used for device detail pages. You can use placeholders like
                                                    <code>{{ '{device_name}' }}</code>, <code>{{ '{brand}' }}</code>, <code>{{ '{image}' }}</code>, <code>{{ '{lowest_price}' }}</code>.
                                                </p>
                                                <div class="form-group">
                                                    <label for="product_schema_template" class="form-label">
                                                        JSON-LD (Product Template)
                                                    </label>
                                                    <textarea id="product_schema_template"
                                                              class="form-control @error('product_schema_template') is-invalid @enderror"
                                                              name="product_schema_template"
                                                              rows="10">{{ old('product_schema_template', $schemaSettings->product_schema_template ?? '') }}</textarea>
                                                    @error('product_schema_template')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-check-lg"></i> Save Schema Settings
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

@extends('admin-views.layouts.admin')

@section('title', 'Page Settings')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Page Settings</h3>
                    <p class="text-subtitle text-muted">Configure content for Contact and Tip Us pages.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Page Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Contact Page --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Contact Page</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="contact_page_title" class="form-label">Page Title</label>
                                    <input type="text" name="contact_page_title" id="contact_page_title"
                                        class="form-control" value="{{ $settings->contact_page_title }}"
                                        placeholder="e.g. Contact us">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="contact_form_title" class="form-label">Form Title</label>
                                    <input type="text" name="contact_form_title" id="contact_form_title" class="form-control"
                                        value="{{ $settings->contact_form_title }}" placeholder="e.g. Send us a message">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="contact_page_content" class="form-label">Page Content</label>
                                    <textarea name="contact_page_content" id="contact_page_content"
                                        class="summernote form-control">{{ $settings->contact_page_content }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Header Image</label>
                                    <div class="mb-3">
                                        @if($settings->contact_page_image)
                                            <img src="{{ asset('storage/' . $settings->contact_page_image) }}"
                                                alt="Contact Header" class="img-fluid rounded border p-2 mb-2"
                                                style="max-height: 150px;">
                                        @endif
                                        <input type="file" name="contact_page_image" class="form-control">
                                    </div>
                                    <small class="text-muted">Recommended size: 1600x400px</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tip Us Page --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Tip Us Page</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="tip_us_page_title" class="form-label">Page Title</label>
                                    <input type="text" name="tip_us_page_title" id="tip_us_page_title" class="form-control"
                                        value="{{ $settings->tip_us_page_title }}" placeholder="e.g. Tip us">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tip_us_form_title" class="form-label">Form Title</label>
                                    <input type="text" name="tip_us_form_title" id="tip_us_form_title" class="form-control"
                                        value="{{ $settings->tip_us_form_title }}" placeholder="e.g. Tip us form">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tip_us_page_content" class="form-label">Page Content</label>
                                    <textarea name="tip_us_page_content" id="tip_us_page_content"
                                        class="summernote form-control">{{ $settings->tip_us_page_content }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Header Image</label>
                                    <div class="mb-3">
                                        @if($settings->tip_us_page_image)
                                            <img src="{{ asset('storage/' . $settings->tip_us_page_image) }}"
                                                alt="Tip Us Header" class="img-fluid rounded border p-2 mb-2"
                                                style="max-height: 150px;">
                                        @endif
                                        <input type="file" name="tip_us_page_image" class="form-control">
                                    </div>
                                    <small class="text-muted">Recommended size: 1600x400px</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-5">
                    <button type="submit" class="btn btn-primary btn-lg">Save Page Settings</button>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
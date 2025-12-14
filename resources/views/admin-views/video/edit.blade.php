@extends('admin-views.layouts.admin')
@section('title', 'Edit Video')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Video</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.videos.index') }}">Videos</a></li>
                            <li class="breadcrumb-item active">Edit Video</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Video Information</h4>
                        </div>
                        <div class="card-body">
                            <form class="form" action="{{ route('admin.videos.update') }}" method="POST"
                                enctype="multipart/form-data" id="deviceForm">
                                @csrf
                                
                                @include('admin-views.video._form')

                                <!-- Include SEO Fields -->
                                 <div class="row mb-4">
                                    <div class="col-12">
                                         @include('components.seo-fields', ['model' => null])
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-check-lg"></i> Update Video
                                        </button>
                                        <a href="{{ route('admin.devices.index') }}" class="btn btn-light-secondary">
                                            <i class="bi bi-arrow-left"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

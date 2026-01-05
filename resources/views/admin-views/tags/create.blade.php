@extends('admin-views.layouts.admin')
@section('title', 'Add New Tag')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Add New Tag</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
                            <li class="breadcrumb-item active">Add New Tag</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section id="basic-vertical-layouts">
        <div class="row match-height justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">New Tag Details</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('admin.tags.store') }}" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name">Tag Name</label>
                                                <input type="text" id="name"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    placeholder="e.g. Samsung, Mobile News" value="{{ old('name') }}"
                                                    required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="type">Tag Type</label>
                                                <select id="type" name="type"
                                                    class="form-control @error('type') is-invalid @enderror" required>
                                                    <option value="" disabled selected>Select Tag Type</option>
                                                    <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News
                                                    </option>
                                                    <option value="review" {{ old('type') == 'review' ? 'selected' : '' }}>
                                                        Review</option>
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_popular"
                                                    id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_popular">
                                                    Is Popular? (Will show in popular tags section)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Save Tag</button>
                                            <a href="{{ route('admin.tags.index') }}"
                                                class="btn btn-light-secondary me-1 mb-1">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
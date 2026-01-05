@extends('admin-views.layouts.admin')

@section('title', 'Add New User')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add New User</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Information</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" 
                                        name="name" placeholder="Enter full name" value="{{ old('name') }}">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                        name="email" placeholder="Enter email address" value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="role" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" name="role" id="role">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" placeholder="Enter password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" id="password_confirmation" class="form-control" 
                                        name="password_confirmation" placeholder="Confirm password">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Profile Picture</label>
                                    <input type="file" id="image" class="form-control @error('image') is-invalid @enderror" name="image">
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-person-plus"></i> Create User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light-secondary">
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
@endsection
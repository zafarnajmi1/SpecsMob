@extends('admin-views.layouts.admin')
@section('title', 'Edit Device')
@php
    $defaultCategories = [
        'Network' => ['Technology', '2G bands', '3G bands', '4G bands', 'Speed'],
        // 'Launch' => ['Announced', 'Status'],
        // 'Body' => ['Dimensions', 'Weight', 'Build', 'SIM', 'Other Features'],
        // 'Display' => ['Type', 'Size', 'Resolution', 'Protection', 'Other Features'],
        // 'Platform' => ['OS', 'Chipset', 'CPU', 'GPU'],
        // 'Memory' => ['Card slot', 'Internal'],
        // 'Main Camera' => ['Modules', 'Features', 'Video'],
        // 'Selfie Camera' => ['Modules', 'Features', 'Video'],
        // 'Sound' => ['Loudspeaker', '3.5mm jack', 'Other Features'],
        // 'Comms' => ['WLAN', 'Bluetooth', 'Positioning', 'NFC', 'Radio', 'USB'],
        // 'Features' => ['Sensors', 'Other'],
        // 'Battery' => ['Type', 'Charging', 'Other'],
        // 'Misc' => ['Colors', 'Models', 'SAR', 'Price']
    ];
@endphp

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Device</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.devices.index') }}">Devices</a></li>
                        <li class="breadcrumb-item active">Edit Device</li>
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
                        <h4 class="card-title">Device Information</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" action="{{ route('admin.devices.update', $device->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('admin-views.devices.partials._form')

                                <!-- Include SEO Fields -->
                                 <div class="row mb-4">
                                    <div class="col-12">
                                         @include('components.seo-fields', ['model' => $device ?? null])
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="bi bi-check-lg"></i> Update Device
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

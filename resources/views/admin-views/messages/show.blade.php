@extends('admin-views.layouts.admin')
@section('title', 'View Message')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>View Message</h3>
                    <p class="text-subtitle text-muted">Detailed view of the submission</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Message Content</h4>
                            <span class="badge {{ $message->type == 'contact' ? 'bg-primary' : 'bg-warning' }}">
                                {{ ucfirst($message->type) }} Submission
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="mb-2">Subject: {{ $message->subject ?? '(No Subject)' }}</h5>
                                <p class="text-muted mb-0">Received on:
                                    {{ $message->created_at->format('F j, Y \a\t H:i') }}</p>
                            </div>

                            <div class="message-body p-4 bg-light rounded"
                                style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.6;">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Delete Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sender Details</h4>
                        </div>
                        <div class="card-body">
                            @if($message->type == 'contact')
                                <div class="mb-3">
                                    <label class="fw-bold text-muted small text-uppercase">Name</label>
                                    <p class="mb-0 fs-5">{{ $message->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-muted small text-uppercase">Email</label>
                                    <p class="mb-0 fs-5">
                                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    </p>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> This is an anonymous tip submission.
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="fw-bold text-muted small text-uppercase">IP Address</label>
                                <p class="mb-0">{{ $message->ip_address ?? 'Unknown' }}</p>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <label class="fw-bold text-muted small text-uppercase mb-2">Internal Status</label>
                                <div>
                                    @if($message->is_read)
                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Marked as
                                            Read</span>
                                    @else
                                        <form action="{{ route('admin.messages.mark-as-read', $message->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
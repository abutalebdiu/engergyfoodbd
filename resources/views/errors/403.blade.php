@extends('admin.layouts.app', ['title' => '403 Error'])

@section('panel')
    <div class="d-flex justify-content-center align-items-center vh-70">
        <div class="text-center">
            <div class="display-3 fw-bold text-danger">403</div>
            <p class="fs-4">
                <strong>Access Denied!</strong> You do not have permission to view this page.
            </p>
            <p class="text-muted">
                Please check with your administrator if you believe this is a mistake.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-arrow-left"></i> Go to Dashboard
            </a>
        </div>
    </div>
@endsection

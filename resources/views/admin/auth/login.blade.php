@extends('admin.layouts.master', ['title' => 'Admin Login'])
@section('content')
    <main class="authentication-content login-bg">
        <div class="container-fluid">
            <div class="authentication-card">
                <div class="overflow-hidden shadow card rounded-2">
                    <div class="p-4 card-body p-sm-5">
                        <h5 class="text-center card-title">{{ __(gs('site_name')) }}</h5>
                        <p class="mb-5 text-center card-text">@lang('Admin Login') </p>
                        <form class="form-body" action="{{ route('admin.login') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">@lang('Email/Username')</label>
                                    <div class="ms-auto position-relative">
                                        <div class="px-3 position-absolute top-50 translate-middle-y search-icon"><i
                                                class="bi bi-person-fill"></i></div>
                                        <input type="text" class="form-control radius-30 ps-5"
                                            value="{{ old('username') }}" name="username" placeholder="@lang('Enter Email/Username')"
                                            required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">@lang('Password')</label>
                                    <div class="ms-auto position-relative">
                                        <div class="px-3 position-absolute top-50 translate-middle-y search-icon"><i
                                                class="bi bi-lock-fill"></i></div>
                                        <input type="password" class="form-control radius-30 ps-5" name="password"
                                            placeholder="@lang('Enter Password')" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="remember" type="checkbox" id="remember">
                                        <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                                    </div>
                                </div>
                                <div class="col-6 text-end"> <a
                                        href="{{ route('admin.password.reset') }}">@lang('Forgot Password?') </a>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary radius-30">@lang('Sign In')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

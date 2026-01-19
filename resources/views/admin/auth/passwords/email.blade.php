@extends('admin.layouts.master', ['title' => 'Account Recovery'])
@section('content')
    <main class="authentication-content login-bg">
        <div class="container-fluid">
            <div class="authentication-card">
                <div class="overflow-hidden shadow card rounded-2">
                    <div class="p-4 card-body p-sm-5">
                        <h5 class="mb-5 text-center card-title">@lang('Forgot Password')</h5>
                        <form action="{{ route('admin.password.reset') }}" method="POST" class="login-form verify-gcaptcha">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">@lang('Email')</label>
                                    <div class="ms-auto position-relative">
                                        <div class="px-3 position-absolute top-50 translate-middle-y search-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                        <input type="email" class="form-control radius-30 ps-5"
                                            value="{{ old('email') }}" name="email" placeholder="@lang('Enter Email')"
                                            required>
                                    </div>
                                </div>
                                @php
                                    $admin = true;
                                @endphp
                                <x-captcha :$admin="$admin"/>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary radius-30">@lang('Submit')</button>
                                    </div>
                                </div>
                                <div class="text-center col-12 mt-2">
                                    <a href="{{ route('admin.login') }}"> @lang('Login Here') </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

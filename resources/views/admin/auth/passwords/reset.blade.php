@extends('admin.layouts.master',['title'=>'Reset Password'])
@section('content')
<main class="authentication-content login-bg">
    <div class="container-fluid">
        <div class="authentication-card">
            <div class="overflow-hidden shadow card rounded-2">
                <div class="p-4 card-body p-sm-5">
                    <h5 class="mb-5 text-center card-title">@lang('Reset Your Password')</h5>
                    <form action="{{ route('admin.password.change') }}" method="POST" class="login-form">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">


                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">@lang('New Password')</label>
                                <div class="ms-auto position-relative">
                                    <div class="px-3 position-absolute top-50 translate-middle-y search-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <input type="password" class="form-control radius-30 ps-5"
                                     name="password" placeholder="@lang('Enter new password')"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <div class="ms-auto position-relative">
                                    <div class="px-3 position-absolute top-50 translate-middle-y search-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <input type="password" class="form-control radius-30 ps-5"
                                     name="password_confirmation" placeholder="@lang('Enter Confirm password')"
                                        required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary radius-30">@lang('Submit')</button>
                                </div>
                            </div>
                            <div class="text-center col-12">
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


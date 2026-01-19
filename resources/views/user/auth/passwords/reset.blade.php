@extends('web.layouts.frontend',['title' => 'Reset Password'])
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 col-xl-5">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title text-center">@lang('Reset Password')</h5>
                </div>
                <div class="card-body px-4">
                    <div class="mb-4">
                        <p>@lang('Your account is verified successfully. Now you can change your password')</p>
                    </div>
                    <form action="{{ route('user.password.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group mb-3">
                            <label class="form-label">@lang('Password')</label>
                            <input type="password" class="form-control  @if(gs('secure_password')) secure-password @endif" name="password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" class="form-control " name="password_confirmation" required>
                        </div>
                        <x-captcha/>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-base w-100"> @lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('global/js/secure_password.js') }}"></script>
    @endpush
@endif

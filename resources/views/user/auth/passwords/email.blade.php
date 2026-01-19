@extends('web.layouts.frontend',['title'=> 'Forgot Password'])
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 col-xl-5">
            <div class="card custom-card">
                <div class="card-header">
                    <h5 class="card-title"> @lang('Forgot Password') </h5>
                </div>
                <div class="card-body px-4">
                    <div class="mb-4">
                        <p>@lang('To initiate account recovery, kindly provide either your email or username for account identification.')</p>
                    </div>
                    <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                        @csrf
                        <div class="form-group pb-4">
                            <label class="form-label">@lang('Email or Username')</label>
                            <input type="text" class="form-control " name="value" value="{{ old('value') }}" required autofocus="off">
                        </div>

                        <x-captcha />

                        <div class="form-group">
                            <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

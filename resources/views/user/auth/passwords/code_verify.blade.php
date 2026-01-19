@extends('web.layouts.frontend',['title' => 'Verify Email'])
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5">
            <div class="card custom-card">
                <div class="card-body px-4">
                    <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="verification-text">@lang('A 6 digit verification code sent to your email address') :  {{ showEmailAddress($email) }}</p>
                        <input type="hidden" name="email" value="{{ $email }}">

                        @include('partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                        </div>

                        <div class="form-group mt-3 text-center">
                            <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

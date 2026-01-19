@extends('web.layouts.frontend')
@section('content')
<div class="container py-5">
   <div class="row justify-content-center">
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-body">
                <h5 class="pb-3 text-center border-bottom">@lang('Verify Email Address')</h5>
                <form action="{{route('user.verify.email')}}" method="POST" class="submit-form">
                    @csrf
                    <p class="verification-text">@lang('A 6 digit verification code sent to your email address'):  {{ showEmailAddress(auth()->user()->email) }}</p>

                    @include('partials.verification_code')

                    <div class="mb-3">
                        <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                    </div>

                    <div class="mb-3">
                        <p>
                            @lang('If you don\'t get any code'), <a href="{{route('user.send.verify.code', 'email')}}"> @lang('Try again')</a>
                        </p>

                        @if($errors->has('resend'))
                            <small class="text-danger d-block">{{ $errors->first('resend') }}</small>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
   </div>
</div>
@endsection

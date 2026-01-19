@extends('web.layouts.frontend',['title' => 'Contact Us'])
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7 col-xl-5">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title"> @lang('Contact Us') </h5>
                </div>
                <div class="card-body">
                    <form method="post" action="" class="verify-gcaptcha">
                        @csrf
                        <div class="pb-3 form-group">
                            <label class="form-label">@lang('Name')</label>
                            <input name="name" type="text" class="form-control " value="{{ old('name',@$user->fullname) }}" @if($user && $user->profile_complete) readonly @endif required>
                        </div>
                        <div class="pb-3 form-group">
                            <label class="form-label">@lang('Email')</label>
                            <input name="email" type="email" class="form-control " value="{{  old('email',@$user->email) }}" @if($user) readonly @endif required>
                        </div>
                        <div class="pb-3 form-group">
                            <label class="form-label">@lang('Subject')</label>
                            <input name="subject" type="text" class="form-control " value="{{old('subject')}}" required>
                        </div>
                        <div class="pb-3 form-group">
                            <label class="form-label">@lang('Message')</label>
                            <textarea name="message" wrap="off" class="form-control " required>{{old('message')}}</textarea>
                        </div>
                        <x-captcha />
                        <div class="pb-3 form-group">
                            <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

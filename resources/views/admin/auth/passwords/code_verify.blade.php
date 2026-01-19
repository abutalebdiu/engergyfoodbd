@extends('admin.layouts.master', ['title' => 'Code Verify'])
@section('content')
    <main class="authentication-content login-bg">
        <div class="container-fluid">
            <div class="authentication-card">
                <div class="card shadow rounded-2 overflow-hidden">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5">@lang('Forgot Password')</h5>
                        <form action="{{ route('admin.password.verify.code') }}" method="POST"
                            class="login-form w-100 verify-gcaptcha">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">@lang('Email')</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                        <input type="text" class="form-control radius-30 ps-5"
                                            value="{{ old('code') }}" name="code" placeholder="@lang('Enter Code')"
                                            required>


                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary radius-30">@lang('Submit')</button>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
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

@push('script')
<script>
    (function($) {
        'use strict';
        $('[name=code]').on('input', function() {

            $(this).val(function(i, val) {
                if (val.length >= 6) {
                    $('form').find('button[type=submit]').html(
                        '<i class="las la-spinner fa-spin"></i>');
                    $('form').find('button[type=submit]').removeClass('disabled');
                    $('form')[0].submit();
                } else {
                    $('form').find('button[type=submit]').addClass('disabled');
                }
                if (val.length > 6) {
                    return val.substring(0, val.length - 1);
                }
                return val;
            });

            for (let index = $(this).val().length; index >= 0; index--) {
                $($('.boxes span')[index]).html('');
            }
        });

    })(jQuery)
</script>
@endpush

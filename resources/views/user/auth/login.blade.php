@extends('web.layouts.frontend', ['title' => 'Sign In'])
@section('content')
    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h5 class="card-title text-center">@lang('Sign In')</h5>
                        </div>
                        <div class="card-body px-4">
                            <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">@lang('Username or Email')</label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                                        class="form-control " required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">@lang('Password')</label>
                                    <input id="password" type="password" class="form-control " name="password" required>
                                </div>

                                <x-captcha />

                                <div class="d-flex justify-content-between flex-wrap gap-3 mb-3">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            @lang('Remember Me')
                                        </label>
                                    </div>

                                    <div>
                                        <a class="fw-bold forgot-pass" href="{{ route('user.password.request') }}">
                                            @lang('Forgot your password?')
                                        </a>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <button type="submit" id="recaptcha" class="btn btn-base w-100">
                                        @lang('Sign In')
                                    </button>
                                </div>
                                <p class="mb-0 text-center">@lang('Don\'t have any account?') <a
                                        href="{{ route('user.register') }}">@lang('Sign Up')</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $('form').on('submit', function() {
            if ($(this).valid()) {
                alert('sadf');
                $(':submit', this).attr('disabled', 'disabled');
            }
        });
    </script>
    @endpush

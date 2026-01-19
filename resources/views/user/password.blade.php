@extends('web.layouts.master',['title' => 'Change Password'])
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="mb-3 form-group ">
                            <label class="form-label">@lang('Current Password')</label>
                            <input type="password" class="form-control " name="current_password" required autocomplete="current-password">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">@lang('Password')</label>
                            <input type="password" class="form-control  @if(gs('secure_password')) secure-password @endif" name="password" required autocomplete="current-password">
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" class="form-control " name="password_confirmation" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                        </div>
                    </form>
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

@push('title')
<h5>@lang('Change Password')</h5>
@endpush

@extends('web.layouts.app')

@section('panel')

    @include('web.partials.header')


    @yield('content')


    @include('web.partials.footer')


    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="text-center cookies-card hide">
            <div class="cookie-consent d-flex flex-wrap align-items-center gap-2">
                <i class="las la-cookie-bite"></i>
                <h4>@lang('Cookies Consent')</h4>
            </div>
            <p class="mt-4 cookies-card_content">{{ $cookie->data_values->short_desc }}</p>
            <div class="mt-4 d-flex gap-3 align-items-center justify-content-start flex-wrap">
                <a href="javascript:void(0)" class="btn coocke-btn policy">@lang('I understand')</a>
                <a href="{{ route('cookie.policy') }}" class="coocke-link" target="_blank">@lang('learn more')</a>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);
        })(jQuery);
    </script>
@endpush

@php
    $lang = session()->get('lang') == 'ar' ? 'ar' : 'en';
@endphp

<!doctype html>
<html lang="{{ config('app.locale') }}" @if ($lang == 'ar') dir="rtl" @endif itemscope
    itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ __(gs('site_name')) }} - {{ __(@$title ?? '') }}</title>
    @include('partials.seo')
    <!-- Bootstrap CSS -->


    @if ($lang == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.rtl.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/web/css/main.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    @if ($lang == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/web/css/arabic.css') }}" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/web/css/custom.css') }}">

    @stack('style-lib')

    @stack('style')


</head>

<body>


    @yield('panel')


    <script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/web/js/main.js') }}"></script>

    @stack('script-lib')

    @include('partials.plugins')

    @include('partials.notify')

    @stack('script')




    <script>
        (function($) {
            "use strict";

            // $(".langSel").on("click", function() {
            //     var langCode = $(this).data('lang');
            //     window.location.href = "{{ route('home') }}/change/" + langCode;
            // });

            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }

            });

        })(jQuery);
    </script>


</body>

</html>

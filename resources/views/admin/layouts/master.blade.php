@php
    $lang = session()->get('lang') == 'ar' ? 'ar' : 'en';
@endphp

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" @if ($lang == 'ar') dir="rtl" @endif class="semi-dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ siteFavicon() }}">
    <title>{{ __(@$title ?? '') }} - {{ gs('site_name') }} </title>
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link href="{{ asset('assets/admin/css/icons.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    @stack('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}"> --}}
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 6px !important;
        }

        .select2-container--default .select2-selection--single {
            border-radius: .375rem !important;
            height: 42px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 9px !important;
        }

    </style>
    @stack('style')
</head>

<body>
    <div class="wrapper">
        @yield('content')
    </div>

    <!-- Manual AJAX Loader -->
    <div id="manualLoader"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                background:rgba(0,0,0,0.4); z-index:9999;
                display:flex; align-items:center; justify-content:center;">
        <div class="spinner-border text-light" role="status" style="width: 4rem; height: 4rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    {{-- panel --}}
    <script src="{{ asset('assets/admin/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/perfect-scrollbar.js') }}"></script>


    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>


    @include('partials.notify')

    @stack('script-lib')

    <script src="{{ asset('assets/admin/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/admin/js/cuModal.js') }}"></script>

    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('assets/admin/js/flatpickr.js') }}"></script>

    <script src="{{ asset('assets/admin/js/app.js') }}"></script>

    {{-- LOAD NIC EDIT --}}


    <script>
        "use strict";

        $(".langSel").on("click", function() {
            var langCode = $(this).data('lang');
            window.location.href = "{{ route('home') }}/change/" + langCode;
        });


        bkLib.onDomLoaded(function() {
            $(".nicEdit, nicEdit2").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);
    </script>


    <script>
        $("#manualLoader").hide();

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

    </script>


    @stack('script')
</body>

</html>

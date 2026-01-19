@extends('web.layouts.frontend', ['title' => __('homepage')])


@section('content')

    
    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include('sections.' . $sec)
        @endforeach
    @endif

@endsection



@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/web/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/web/css/slick-theme.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/web/js/slick.min.js') }}"></script>
@endpush


@push('script')
    <script>
        $(window).on('resize', function(event) {
            let width = $(document).width()

            if (width < 576) {
                $(".property-type-area-slider").slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    speed: 1800,
                    dots: false,
                    arrows: false,
                    @if (session()->get('lang') == 'ar')
                        rtl: true,
                    @endif
                });
            }
        });

        if ($(window).width() < 576) {
            $(".property-type-area-slider").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                speed: 1800,
                dots: true,
                arrows: false,
                @if (session()->get('lang') == 'ar')
                    rtl: true,
                @endif
            });
        }
    </script>
@endpush

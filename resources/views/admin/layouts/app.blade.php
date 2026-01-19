@extends('admin.layouts.master')

@section('content')
    @include('admin.partials.topnav')

    @include('admin.partials.sidenav')


    <main class="page-content">
        <!--breadcrumb-->
        {{-- <div class="mb-4 page-breadcrumb d-sm-flex align-items-center justify-content-between">
            <div class="mb-2 breadcrumb-title mb-sm-0">{{ __(@$title) }}</div>
            <div>
                @stack('breadcrumb-plugins')
            </div>
        </div> --}}
        <!--end breadcrumb-->

        @yield('panel')
        @yield('content')
    </main>
@endsection

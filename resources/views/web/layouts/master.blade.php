@extends('web.layouts.app')

@section('panel')



    @include('web.partials.header')

    <section class="dashboard-main py-5">
        <div class="container">
            
            @if(auth()->user()->kv == 'unverified')
            <div class="alert alert-info" role="alert">
              <h6 class="alert-heading">@lang('KYC Verification required')</h6>
              <hr>
              <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit.   <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Verify')</a></p>
            </div>
            @elseif(auth()->user()->kv == 'pending')
            <div class="alert alert-warning" role="alert">
                <h6 class="alert-heading">@lang('KYC Verification pending')</h6>
                <hr>
                <p class="mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
              </div>
            @endif
            
            <div class="dashboard">
                <div class="dashboard-head align-items-end flex-wrap gap-4">
                    @include('web.partials.dashboard_header')
                </div>
                <div class="row">
                    <div class="col-12 col-lg-3 d-mobile-menu">
                        <div class="dashboard-sidnav">
                            @include('web.partials.dashboard_sidnav')
                        </div>
                    </div>
                    <div class="col-12 col-lg-9">
                        <div class="dashboard-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.partials.footer')
@endsection

@extends('web.layouts.master', ['title' => 'Dashboard'])
@section('content')
    <div class="row">
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #00A3FF;">
                <div>
                    <i class="bi bi-houses"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo')</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #FF5C00;">
                <div>
                    <i class="bi bi-house-check"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo')</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #A100DC;">
                <div>
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo')</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #FF407D;">
                <div>
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo')</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #0C359E;">
                <div>
                    <i class="bi bi-gear"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo')</h6>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="dashboard-card" style="background: #265073;">
                <div>
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <h3> 05 </h3>
                    <h6>@lang('demo') </h6>
                </div>
            </div>
        </div>
    </div>
@endsection
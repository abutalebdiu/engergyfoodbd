@extends('web.layouts.master')
@section('content')
    <div class="card custom-card">
        <div class="card-body">
            <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                @csrf
                @php
                    $kyc_type = auth()->user()->type . '_kyc';
                @endphp
                <x-viser-form identifier="act" identifierValue="{{ $kyc_type }}" />

                <div class="form-group">
                    <button type="submit" class="btn btn-base w-100">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('title')
    <h5>@lang('KYC Form')</h5>
@endpush

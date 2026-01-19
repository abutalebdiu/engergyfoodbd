@extends('admin.layouts.app', ['title' => 'Mail Config'])
@section('panel')
    <div class="container">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div>
                <h6 class="m-0">{{ __('Email Config') }}</h6>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.setting.createEmail') }}" type="button" class="btn btn-primary btn-sm"> <i
                        class="bi bi-plus-circle"></i> {{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <div class="mb-3">
                    @if(session('success'))
                        <div class="mb-2 alert alert-success">{{session('success')}}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-2 alert alert-warning">{{session('error')}}</div>
                    @endif
                </div>
                @foreach ($providers as $provider)
                    <div class="p-1 mx-1 card">
                        <div class="d-flex justify-content-between">
                            <div class="p-3">
                                <h5>{{ $provider->title }}</h5>
                                <p>{{ $provider->domain }}</p>
                            </div>
                            <div class="p-3">
                                @if($provider->status == 1)
                                <a href="#" class="btn btn-success">Active</a>
                                @else
                                <a href="#" class="btn btn-warning">Inactive</a>
                                @endif

                                <a href="{{ route('admin.setting.edit.email', $provider->id) }}" class="btn btn-info">Edit</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

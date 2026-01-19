@extends('admin.layouts.app', ['title' => 'SMS'])
@section('panel')
    <div class="container-fluid">

        <div class="card">
            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">{{session('error')}}</div>
            @endif
            <div class="card-body">
                @foreach ($providers as $provider)
                    <div class="p-1 mx-1 card">
                        <div class="d-flex justify-content-between">
                            <div class="p-3">
                                <h5>{{ $provider->name }}</h5>
                                <p>{{ $provider->code }}</p>
                            </div>
                            <div class="p-3">
                                @if($provider->status == "Active")
                                <a href="#" class="btn btn-success">@lang('Active)</a>
                                @else
                                <a href="#" class="btn btn-warning">@lang('Inactive')</a>
                                @endif

                                <a href="{{ route('admin.setting.edit.sms', $provider->id) }}" class="btn btn-info">@lang('Edit')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

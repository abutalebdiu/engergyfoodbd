@extends('admin.layouts.app', ['title' => 'Edit SMS'])
@section('panel')
    <div class="container-fluid">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div class="ms-auto">
                <a href="{{ route('admin.setting.sms') }}" type="button" class="btn btn-primary btn-sm"> <i
                        class="bi bi-arrow-counterclockwise"></i> @lang('Back To')</a>
            </div>
        </div>
        <div class="card">
            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">{{session('error')}}</div>
            @endif
            <div class="card-body">
                <form action="{{ route('admin.setting.store.sms', $sms->id) }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="name">@lang('Name')</label>
                                <input type="text" name="name" value="{{ $sms->name }}" class="form-control">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @foreach (json_decode($sms->config, true) as $key => $config)
                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="name">{{ ucwords(str_replace("_", " ", $key)) }}</label>
                                <input type="text" name="{{ $key }}" value="{{ $config }}" class="form-control" required>
                            </div>
                        </div>
                        @endforeach
                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="status">@lang('Group')</label>
                                <select name="status" class="form-control">
                                    <option {{ $sms->status == 'Active' ? 'selected' : '' }} value="Active">@lang('Active')</option>
                                    <option {{ $sms->status == 'Inactive' ? 'selected' : '' }} value="Inactive">@lang('Inactive')</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 col-sm-12">
                            <a href="{{ route('admin.setting.sms') }}" class="px-3 btn btn-warning btn-sm">@lang('Cancel')</a>
                            <button type="submit" class="px-3 btn btn-primary btn-sm">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

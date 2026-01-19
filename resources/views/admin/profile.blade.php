@extends('admin.layouts.app', ['title' => 'profile'])
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-lg-4 mb-30">

            <div class="overflow-hidden card b-radius--5">
                <div class="card-body">
                    <div class="p-3 d-flex bg-primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('adminProfile') . '/' . $admin->image, getFileSize('adminProfile')) }}"
                                alt="@lang('Image')">
                        </div>
                        <div class="ps-3">
                            <h4 class="text-white">{{ __($admin->name) }}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name')
                            <span class="fw-bold">{{ __($admin->name) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="fw-bold">{{ __($admin->username) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span class="fw-bold">{{ $admin->email }}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="pb-2 mb-4 card-title border-bottom">@lang('Profile Information')</h5>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Image')</label>
                                    <x-image-uploader image="{{ $admin->image }}" class="w-100" type="adminProfile" />
                                </div>
                            </div>
                            <div class="col-xxl-7 col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" value="{{ $admin->name }}"
                                        required>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ $admin->email }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary h-45 w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.password') }}" class="btn btn-sm btn-outline-primary"><i
            class="las la-key"></i>@lang('Password Setting')</a>
@endpush

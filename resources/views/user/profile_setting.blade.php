@extends('web.layouts.master', ['title' => 'Profile Setting'])
@section('content')
    <form class="register" action="{{ route('user.profile.setting') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex gap-4 flex-wrap">
                    <div class="user-profile-img">
                        <div>
                            <x-image-uploader image="{{ $user->image }}" class="w-100" type="userProfile"
                                :showSizeFileType=false />
                        </div>
                    </div>
                    <div class="profile-information">
                        <p>{{ $user->name }}</p>
                        <p>{{ '@' . $user->username }}</p>
                        <p>{{ $user->email }}</p>
                        <p>{{ $user->mobile }}</p>
                        <p>{{ @$user->address->country }}</p>
                    </div>
                </div>
            </div>

            <div class="pb-3 form-group col-sm-6">
                <label class="form-label">@lang('Your Name')</label>
                <input type="text" class="form-control " name="name" value="{{ $user->name }}" required>
            </div>

            <div class="pb-3 form-group col-sm-6">
                <label class="form-label">@lang('Address')</label>
                <input type="text" class="form-control " name="address" value="{{ @$user->address->address }}">
            </div>
            <div class="pb-3 form-group col-sm-4">
                <label class="form-label">@lang('State')</label>
                <input type="text" class="form-control " name="state" value="{{ @$user->address->state }}">
            </div>

            <div class="pb-3 form-group col-sm-4">
                <label class="form-label">@lang('Zip Code')</label>
                <input type="text" class="form-control " name="zip" value="{{ @$user->address->zip }}">
            </div>

            <div class="pb-3 form-group col-sm-4">
                <label class="form-label">@lang('City')</label>
                <input type="text" class="form-control " name="city" value="{{ @$user->address->city }}">
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-base w-100">@lang('Update Profile')</button>
        </div>
    </form>
@endsection

@push('title')
    <h5 class="card-title">@lang('Profile Setting')</h5>
@endpush

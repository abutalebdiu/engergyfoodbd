@extends('admin.layouts.app',['title'=> 'Logo & Favicon'])
@section('panel')
    <div class="row mb-none-30">
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 row justify-content-center">
                            <div class="mb-3 form-group col-md-4">
                                <label class="form-label"> @lang('Logo Dark')</label>
                                <x-image-uploader name="logo_dark" :imagePath="siteLogo('dark') . '?' . time()" :size="false" class="w-100" id="uploadLogo" :required="false" :darkMode="true"/>
                            </div>
                            <div class="mb-3 form-group col-md-4">
                                <label class="form-label"> @lang('Logo White')</label>
                                <x-image-uploader name="logo" :imagePath="siteLogo() . '?' . time()" :size="false" class="w-100" id="uploadLogo" :required="false" />
                            </div>
                            <div class="mb-3 form-group col-md-4">
                                <label class="form-label"> @lang('Favicon')</label>
                                <x-image-uploader name="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false" class="w-100" id="uploadFavicon" :required="false" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

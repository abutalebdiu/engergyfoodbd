@extends('admin.layouts.app', ['title' => 'Edit Hotel Information'])
@section('panel')
    <form action="{{ route('admin.hotel.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Hotel Information <a href="{{ route('admin.hotel.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Hotel list</a></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Hotel Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{ $hotel->name }}">
                        </div>
                    </div>

                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Email') </label>
                            <input class="form-control" type="email" name="email" value="{{ $hotel->email }}">
                        </div>
                    </div>

                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Mobile Number') </label>
                            <input type="number" name="mobile" value="{{ $hotel->mobile }}" id="mobile"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="pb-3 col-md-12">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Address')</label>
                            <textarea class="form-control" name="address">{{ $hotel->address }}</textarea>
                        </div>
                    </div>

                    <div class="pb-3 col-md-3">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Contact Person Name')</label>
                            <input class="form-control" type="text" name="contact_name"
                                value="{{ $hotel->contact_name }}">
                        </div>
                    </div>

                    <div class="pb-3 col-md-3">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Contact Person Mobile')</label>
                            <input class="form-control" type="text" name="contact_mobile"
                                value="{{ $hotel->contact_mobile }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-3">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Contact Person Email')</label>
                            <input class="form-control" type="text" name="contact_email"
                                value="{{ $hotel->contact_email }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-3">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Room Rate')</label>
                            <input class="form-control" type="text" name="room_rate" value="{{ $hotel->room_rate }}">
                        </div>
                    </div>


                    <div class="pb-3 col-md-12">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Note')</label>
                            <textarea name="note" id="note" rows="3" class="form-control">{{ $hotel->note }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 mt-4">@lang('Submit')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

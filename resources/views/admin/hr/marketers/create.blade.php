@extends('admin.layouts.app', ['title' => 'Add New Marketer'])
@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Add New Marketer</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.marketer.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-list"></i> Marketer List</a>
        </div>
    </div>
    <!--breadcrumb-->
    <form action="{{ route('admin.marketer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Mobile') </label>
                            <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Email') </label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Address')</label>
                            <input class="form-control" type="text" name="address" value="{{ old('address') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Commission') %</label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('Status')</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Active"> Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <input type="submit" class="btn btn-primary float-end mt-4"
                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                        value="@lang('Submit')">
                </div>
            </div>
        </div>
    </form>
@endsection

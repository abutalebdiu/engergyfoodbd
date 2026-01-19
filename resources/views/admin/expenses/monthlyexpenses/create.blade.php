@extends('admin.layouts.app', ['title' => 'Add New Monthly Expenses'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.monthlyexpense.index') }}" class="btn btn-sm btn-outline-primary">@lang('Monthly Expenses')</a>
        </div>
    @endpush

    <form action="{{ route('admin.monthlyexpense.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Add New Monthly Expenses') </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-12">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-12">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Description')</label>
                            <textarea class="form-control" rows="5" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.monthlyexpense.index') }}"
                            class="btn btn-outline-info float-start">Back</a>
                        <input type="submit" class="btn btn-primary float-end"
                            onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                            value="@lang('Submit')">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

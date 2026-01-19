@extends('admin.layouts.app', ['title' => 'Edit Leave Type'])
@section('panel')
    <form action="{{ route('admin.leavetype.update', $leavetype->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Edit Leave Type
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required
                                value="{{ $leavetype->name }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Short Code')</label>
                            <input class="form-control" type="text" name="short_code" required
                                value="{{ $leavetype->short_code }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <a href="{{ route('admin.leavetype.index') }}"
                            class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

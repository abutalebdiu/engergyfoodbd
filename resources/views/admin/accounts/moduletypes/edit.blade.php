@extends('admin.layouts.app', ['title' => 'Edit Module Type'])
@section('panel')
    <form action="{{ route('admin.moduletype.update', $moduletype->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Module Type Information <a href="{{ route('admin.moduletype.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Module Type list</a></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required
                                value="{{ $moduletype->name }}">
                        </div>
                    </div>
                </div>
                <div class="pb-3 col-12 col-md-6">
                    <div class="form-group">
                        <label class="form-label text-capitalize">@lang('Short Code')</label>
                        <input class="form-control" type="text" name="short_code" required
                            value="{{ $moduletype->short_code }}">
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

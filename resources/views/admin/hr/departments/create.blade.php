@extends('admin.layouts.app', ['title' => 'Add New Department'])
@section('panel')
    <form action="{{ route('admin.department.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">@lang('Department')
                    <a href="{{ route('admin.department.index') }}" class="btn btn-primary float-end"> <i
                            class="fa fa-plus"></i>
                        @lang('Department List')
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Position')</label>
                            <input class="form-control" type="number" name="position" value="{{ old('position') }}"
                                required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <a href="{{ route('admin.department.index') }}"
                            class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

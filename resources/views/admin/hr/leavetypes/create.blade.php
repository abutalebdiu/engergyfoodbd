@extends('admin.layouts.app', ['title' => 'Add New Leave Type'])
@section('panel')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.leavetype.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="pb-3 col-md-4">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" required
                                        value="{{ old('name') }}">
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
        </div>
    </div>
@endsection

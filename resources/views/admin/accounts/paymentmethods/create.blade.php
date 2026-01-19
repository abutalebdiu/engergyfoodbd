@extends('admin.layouts.app', ['title' => 'Add New Payment Method'])
@section('panel')
    <form action="{{ route('admin.paymentmethod.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Payment Method <a href="{{ route('admin.paymentmethod.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Payment Method List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
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

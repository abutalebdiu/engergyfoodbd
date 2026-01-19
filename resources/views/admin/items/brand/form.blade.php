@extends('admin.layouts.app',['title'=> @$title])
@section('panel')
    <form action="{{ isset ($brand) ? route('admin.items.itemBrand.store', $brand->id) : route('admin.items.itemBrand.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ @$title }}<a href="{{ route('admin.items.itemBrand.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Item Brand Lists</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required
                                value="{{ old('name', @$brand->name) }}">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="mt-4 btn btn-primary w-100">@lang('Submit')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

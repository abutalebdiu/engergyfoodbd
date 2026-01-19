@extends('admin.layouts.app', ['title' => 'Add New Asset'])
@section('panel')
    <form action="{{ route('admin.asset.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Add New Asset')
                    <a href="{{ route('admin.asset.index') }}" class="btn btn-primary btn-sm float-end"> <i
                            class="fa fa-arrow-left"></i> @lang('Asset List')</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="pb-3 col-12 col-md-9">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Asset Name')</label>
                            <input class="form-control" type="text" name="title" required value="{{ old('title') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Price')</label>
                            <input type="text" name="price" value="{{ old('price') }}" id="price"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Description')</label>
                            <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Purchase Date')</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" id="purchase_date"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Expired Date')</label>
                            <input type="date" name="expired_date" value="{{ old('expired_date') }}" id="purchase_date"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Image')</label>
                            <input type="file" name="image" value="{{ old('image') }}" id="image"
                                class="form-control">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <a href="{{ route('admin.asset.index') }}" class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

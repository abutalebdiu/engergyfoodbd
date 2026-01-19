@extends('admin.layouts.app', ['title' => 'Add New Product'])
@section('panel')
    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Add New Product')
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary btn-sm float-end"> <i
                            class="fa fa-arrow-left"></i> @lang('Products List')</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Product Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Weight')</label>
                            <input type="text" class="form-control" name="weight" value="{{ old('weight') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Department') <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value=""> -- Select -- </option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Sale Price')</label>
                            <input type="text" name="sale_price" value="{{ old('sale_price') }}" id="sale_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Store/Shop Price')</label>
                            <input type="text" name="shop_price" value="{{ old('shop_price') }}" id="shop_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Retail Price')</label>
                            <input type="text" name="retail_price" value="{{ old('retail_price') }}" id="retail_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Yeast')</label>
                            <input type="text" name="yeast" value="{{ old('yeast') }}" id="yeast"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Yeast Unit')</label>
                            <input type="text" name="yeast_unit" value="{{ old('yeast_unit') }}" id="yeast_unit"
                                class="form-control">
                        </div>
                    </div>
                    

                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('PP Item')</label>
                            <select name="pp_item_id" id="pp_item_id" class="form-control select2">
                                <option value="">Select PP Item</option>
                                @foreach($ppitems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('PP Weight')</label>
                            <input type="text" name="pp_weight" value="{{ old('pp_weight') }}" id="pp_weight"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Box Item')</label>
                            <select name="box_item_id" id="box_item_id" class="form-control select2">
                                <option value="">Select Box Item</option>
                                @foreach($boxitems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Striker Item')</label>
                            <select name="striker_item_id" id="striker_item_id" class="form-control select2">
                                <option value="">Select Striker Item</option>
                                @foreach($strickeritems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection

@include('components.select2')

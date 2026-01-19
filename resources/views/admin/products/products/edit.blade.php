@extends('admin.layouts.app', ['title' => 'Edit Product'])
@section('panel')
    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Edit Product')
                    <a href="{{ route('admin.product.index') }}" class="btn btn-primary btn-sm float-end"> <i
                            class="fa fa-arrow-left"></i> @lang('Products List')</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Product Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{ $product->name }}">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Weight')</label>
                            <input type="text" class="form-control" name="weight" value="{{ $product->weight }}">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Weight Gram')</label>
                            <input type="number" class="form-control" name="weight_gram" value="{{ $product->weight_gram }}">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Department') <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value=""> -- Select -- </option>
                                @foreach ($departments as $department)
                                    <option {{ $department->id == $product->department_id ? "selected" : "" }} value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Sale Price')</label>
                            <input type="text" name="sale_price" value="{{ $product->sale_price }}" id="sale_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Store/Shop Price')</label>
                            <input type="text" name="shop_price" value="{{ $product->shop_price }}" id="shop_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Retail Price')</label>
                            <input type="text" name="retail_price" value="{{ $product->retail_price }}" id="retail_price"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Yeast')</label>
                            <input type="text" name="yeast" value="{{ $product->yeast }}" id="yeast"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Yeast Unit')</label>
                            <input type="text" name="yeast_unit" value="{{ $product->yeast_unit }}" id="yeast_unit"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('PP Item')</label>
                            <select name="pp_item_id" id="pp_item_id" class="form-control select2">
                                <option value="">Select PP Item</option>
                                @foreach($ppitems as $item)
                                <option {{ $product->pp_item_id == $item->id ? "selected" :  "" }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                     <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('PP Weight')</label>
                            <input type="text" name="pp_weight" value="{{ $product->pp_weight }}" id="pp_weight"
                                class="form-control">
                        </div>
                    </div>
                   

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Box Item')</label>
                            <select name="box_item_id" id="box_item_id" class="form-control select2">
                                <option value="">Select Box Item</option>
                                @foreach($boxitems as $item)
                                <option {{ $product->box_item_id == $item->id ? "selected" :  "" }} value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option {{ $product->striker_item_id == $item->id ? "selected" :  "" }}  value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Commission')</label>
                            <input type="text" name="commission" value="{{ $product->commission }}" id="commission"
                                class="form-control">
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Type')</label>
                            <select name="type" id="type" class="form-select">
                                <option value="1">@lang('Update Product Only')</option>
                                <option value="2">@lang('Update Customer Product Price')</option>
                                <option value="3">@lang('Update Customer Commission')</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <a href="{{ route('admin.product.index') }}"
                            class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
 
@endsection

@include('components.select2')
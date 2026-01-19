@extends('admin.layouts.app', ['title' => 'Set Recipe'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                Set Recipe for {{ $product->name }}  /   @lang('Yeast') : {{ en2bn($product->yeast) }} {{ $product->yeast_unit }}
                <a href="{{ route('admin.product.index') }}" class="btn btn-info btn-sm float-end">
                    <i class="fa fa-list"></i> @lang('Products List')
                </a>

                <a href="" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#assignItem">
                    <i class="fa fa-plus"></i> Add Item</a>
            </h5>
        </div>
        <div class="card-body">
            <p> PP Name : {{ optional($product->ppitem)->name }} - PP Price: {{ optional($product->ppitem)->price }} - PP Weight: {{ $product->pp_weight }}- Per PP Cost: 
                {{ (optional($product->ppitem)->price/1000) * $product->pp_weight }} </p>
            <p>Box Item Name: {{  $product->boxitem?->name  }} - Box Price: {{ $product->boxitem?->price }} </p>
            <p> Striker Name: {{  $product->striker?->name  }} - Striker Price: {{ $product->striker?->price }} </p>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Per Product QTY</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->productrecipe as $recipe)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($recipe->item)->name }}</td>
                            <td>{{ en2bn($recipe->qty) }}</td>
                            <td>{{ en2bn($recipe->qty_per_product) }}</td>
                            <td>{{ optional($recipe->unit)->name }}</td>
                            <td>
                                <a href="" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#EditRecipe_{{ $recipe->id }}"> <i class="bi bi-pencil"></i>
                                    Edit</a>

                                <a href="javascript:;" data-id="{{ $recipe->id }}"
                                    data-question="@lang('Are you sure you want to delete this item?')"
                                    data-action="{{ route('admin.productrecipe.destroy', $recipe->id) }}"
                                    class="btn btn-danger btn-sm confirmationBtn">
                                    <i class="bi bi-trash"></i> @lang('Delete')
                                </a>
                            </td>

                            <div class="modal fade" id="EditRecipe_{{ $recipe->id }}" tabindex="-1" aria-hidden="true"
                                style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Recipe Item</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.productrecipe.update', $recipe->id) }}"
                                                method="post">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" id="product_id"
                                                    value="{{ $recipe->product_id }}">
                                                <div class="row">
                                                    <div class="col-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Items</label>
                                                            <select name="item_id" id="item_id"
                                                                class="form-select select2 item_id" required>
                                                                <option value="">Select Item</option>
                                                                @foreach ($items as $item)
                                                                    <option
                                                                        {{ $recipe->item_id == $item->id ? 'selected' : '' }}
                                                                        value="{{ $item->id }}">{{ $item->name }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                            <div class="text-danger">{{ $errors->first('item_id') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12 my-4">
                                                        <div class="form-group">
                                                            <label for="">Quantity</label>
                                                            <input type="text" name="qty" class="form-control"
                                                                value="{{ $recipe->qty }}" required>
                                                            <div class="text-danger">{{ $errors->first('qty') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Unit</label>
                                                            <select name="unit_id" id="unit_id"
                                                                class="form-select unit_id" required>
                                                                <option value="">Select Unit</option>
                                                                @foreach ($units as $unit)
                                                                    <option
                                                                        {{ $recipe->unit_id == $unit->id ? 'selected' : '' }}
                                                                        value="{{ $unit->id }}">{{ $unit->name }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                            <div class="text-danger">{{ $errors->first('unit_id') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="assignItem" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.productrecipe.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="">Items</label>
                                    <select name="item_id" id="item_id" class="form-select select2 item_id" required>
                                        <option value="">Select Item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                    <div class="text-danger">{{ $errors->first('item_id') }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 my-4">
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input type="text" name="qty" class="form-control"
                                        value="{{ old('qty') }}" required>
                                    <div class="text-danger">{{ $errors->first('qty') }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="">Unit</label>
                                    <select name="unit_id" id="unit_id" class="form-select unit_id" required>
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach

                                    </select>
                                    <div class="text-danger">{{ $errors->first('unit_id') }}</div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

@extends('admin.layouts.app', ['title' => @$title])
@section('panel')
    <form id="itemForm" action="{{ isset($item) ? route('admin.items.item.store', $item->id) : route('admin.items.item.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ @$title }}<a href="{{ route('admin.items.item.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Item Lists</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required
                                value="{{ old('name', @$item->name) }}">
                        </div>
                    </div>


                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Category')</label>
                            <select class="form-control select2" name="item_category_id">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('item_category_id', @$item->item_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                <option value="">@lang('Select Category')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Unit')</label>
                            <select class="form-control select2" name="unit_id">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id', @$item->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                                <option value="">@lang('Select Unit')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize"> ওজন (গ্রাম)</label>
                            <input class="form-control" type="text" name="weight_gram" required
                                value="{{ old('weight_gram', @$item->weight_gram) }}">
                        </div>
                    </div>
                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Price')</label>
                            <input class="form-control" type="text" name="price" required
                                value="{{ old('price', @$item->price) }}">
                        </div>
                    </div>

                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Opening Quantity')</label>
                            <input class="form-control" type="text" name="opening_qty"
                                value="{{ old('opening_qty', @$item->opening_qty) }}">
                        </div>
                    </div>

                    <div class="col-md-12 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Description')</label>
                            <textarea class="form-control" name="description">{{ old('description', @$item->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6 py-2">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Image')</label>
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>

                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" id="submitBTN" class="mt-4 btn btn-primary w-100">@lang('Submit')</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('script')
<script>
    $(document).ready(function() {
        $("#itemForm").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);

            storeData(form, "submitBTN");
        });
    });
</script>
@endpush

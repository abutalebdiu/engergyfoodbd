@extends('admin.layouts.app', ['title' => 'Edit Item Stock'])
@section('panel')
    <form action="{{ route('admin.itemtstock.update', $itemstock->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Edit Item Stock
                    <a href="{{ route('admin.itemtstock.index') }}" class="btn btn-outline-primary btn-sm float-end">
                        <i class="fa fa-list"></i> Order List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Item Name')</label>
                            <input class="form-control" type="text" value="{{ $itemstock->item->name }}" readonly>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Physical Stock')</label>
                            <input type="text" name="physical_stock" class="form-control"
                                value="{{ $itemstock->physical_stock }}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <a href="{{ route('admin.itemtstock.index') }}" class="btn btn-outline-info float-start">Back</a>
                    <button type="submit" class="btn btn-primary float-end">@lang('Submit')</button>
                </div>

            </div>
        </div>

    </form>
@endsection

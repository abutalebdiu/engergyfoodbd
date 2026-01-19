@extends('admin.layouts.app', ['title' => 'Edit Order Return'])
@section('panel')
    <form action="{{ route('admin.orderreturn.update', $orderreturn->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Edit Order Return
                    <a href="{{ route('admin.orderreturn.index') }}" class="btn btn-outline-primary btn-sm float-end">
                        <i class="fa fa-list"></i> Order Return List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ $orderreturn->customer?->name }}" disabled>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Order UID')</label>
                            <input class="form-control" type="text" value="{{ $orderreturn->order?->oid }}" disabled>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ $orderreturn->date ? $orderreturn->date : date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('admin.orderreturn.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

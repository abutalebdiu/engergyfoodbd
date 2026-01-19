@extends('admin.layouts.app', ['title' => __('Edit Production Expense')])
@section('panel')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.productionloss.update',$productionloss->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            @lang('Edit Production Expense')
                            <a href="{{ route('admin.productionloss.index') }}" class="btn btn-primary btn-sm float-end"><i class="fa fa-list"></i> @lang('Productions Loss List')</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">                         
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Item')</label>
                                    <select name="item_id" id="item_id" class="form-control select2 item_id">
                                        <option value="">@lang('Select')</option>
                                        @foreach ($items as $item)
                                            <option {{ $productionloss->item_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Qty')</label>
                                    <input class="form-control" type="text" name="qty" required
                                        value="{{ en2bn($productionloss->qty) }}">
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Department')</label>
                                    <select name="department_id" id="department_id" class="form-control select2 department_id">
                                        <option value="">@lang('Select')</option>
                                        @foreach ($departments as $department)
                                            <option   {{ $productionloss->department_id == $department->id ? "selected" : "" }}   value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Date')</label>
                                    <input class="form-control" type="date" name="date" required
                                        value="{{ $productionloss->date }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a href="{{ route('admin.productionloss.index') }}"
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

@include('components.select2')
@extends('admin.layouts.app',['title'=> @$title])
@section('panel')
    <form action="{{ isset ($unit) ? route('admin.items.unit.store', $unit->id) : route('admin.items.unit.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ @$title }}<a href="{{ route('admin.items.unit.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Unit Lists</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required
                                value="{{ old('name', @$unit->name) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Symbol')</label>
                            <input class="form-control" type="text" name="symbol" required
                                value="{{ old('symbol', @$unit->symbol) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Base Unit')</label>
                            <input class="form-control" type="text" name="base_unit" required
                                value="{{ old('base_unit', @$unit->base_unit) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Value')</label>
                            <input class="form-control" type="text" name="value" required
                                value="{{ old('value', @$unit->value) }}">
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

@extends('admin.layouts.app',['title'=>'Add New Service Invoice'])
@section('panel')
    <form action="{{ route('admin.serviceinvoice.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Service Invoice <a href="{{ route('admin.serviceinvoice.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Service Invoice List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Customers')</label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($customers as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Term') <span
                                    class="text-danger">*</span></label>
                            <select name="term" id="term" class="form-select" required>
                                <option value=""> -- Select -- </option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-select" required>
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Year') <span
                                    class="text-danger">*</span></label>
                            <select name="year_id" id="year_id" class="form-select" required>
                                <option value=""> -- Select -- </option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                     
                    
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 mt-4">@lang('Submit')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

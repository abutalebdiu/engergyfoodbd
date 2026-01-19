@extends('admin.layouts.app',['title'=>'Add New Festival Bonus'])
@section('panel')

        <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
            <div>
                <h4>Add New Festival Bonus</h4>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.festivalbonus.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                        class="bi bi-arrow-counterclockwise"></i> Back To Festival Bonus list</a>
            </div>
        </div>
        <!--breadcrumb-->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.festivalbonus.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 col-md-4 py-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 py-2">
                            <div class="form-group">
                                <label for="name">Bonus Eligible Date</label>
                                <input type="date" name="date" value="{{ old('date') }}" class="form-control">
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 py-2">
                            <div class="form-group">
                                <label for="name">Percentage</label>
                                <input type="text" name="percentage" value="{{ old('percentage') }}" class="form-control">
                                <span class="text-danger">{{ $errors->first('percentage') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 py-2">
                            <div class="form-group">
                                <label for="name">Note</label>
                                <textarea name="note" class="form-control"></textarea>
                                <span class="text-danger">{{ $errors->first('percentage') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 py-2">
                            <div class="form-group">
                                <label for="year">Year</label>
                                <input type="text" name="year" value="{{ old('year') }}" class="form-control">
                                <span class="text-danger">{{ $errors->first('year') }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 py-2">
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-12  mt-3">
                            <a href="{{ route('admin.salarytype.index') }}" class="btn btn-warning px-3">@lang('Cancel')</a>
                            <button type="submit" class="btn btn-primary px-3">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

@endsection

@extends('admin.layouts.app', ['title' => 'Edit Festival Bonus'])
@section('panel')
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h4>Edit Festival Bonus</h4>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.festivalbonusdetail.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-arrow-counterclockwise"></i> Back To Festival Bonus</a>
        </div>
    </div>
    <!--breadcrumb-->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.festivalbonusdetail.update', $festivalbonusdetail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12 col-md-4 py-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" value="{{ $festivalbonusdetail->employee->name }}" class="form-control"
                                disabled>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 py-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" value="{{ $festivalbonusdetail->employee->department->name }}" class="form-control"
                                disabled>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 py-2">
                        <div class="form-group">
                            <label for="name">Join Date</label>
                            <input type="text" value="{{ $festivalbonusdetail->employee->joindate }}" class="form-control"
                                disabled>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 py-2">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" value="{{ $festivalbonusdetail->amount }}"
                                class="form-control">
                            <span class="text-danger">{{ $errors->first('year') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-12  mt-3">
                        <button type="submit" class="btn btn-primary px-3">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

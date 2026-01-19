@extends('admin.layouts.app',['title'=>'Edit Salary Deduction'])
@section('panel')
    <form action="{{ route('admin.salarydeduction.update', $salarydeduction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Salary Deduction<a href="{{ route('admin.salarydeduction.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Salary Deduction List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Employee') <span
                                    class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($employees as $item)
                                    <option {{ $salarydeduction->employee_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $item)
                                    <option {{ $salarydeduction->month_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Year') <span
                                    class="text-danger">*</span></label>
                            <select name="year_id" id="year_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($years as $year)
                                    <option  {{ $salarydeduction->year_id == $year->id ? 'selected' : '' }} value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ $salarydeduction->amount }}"
                                required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Note') <span
                                    class="text-danger">*</span></label>
                            <textarea name="note" id="note" class="form-control">{{ $salarydeduction->note }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <input type="submit" class="btn btn-primary float-end"
                    onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                    value="@lang('Submit')">
                </div>
            </div>
        </div>
    </form>
@endsection

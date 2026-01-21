@extends('admin.layouts.app', ['title' => 'Add New Employee Bonus'])
@section('panel')
    <form action="{{ route('admin.salarybonussetup.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Employee Bonus <a href="{{ route('admin.salarybonussetup.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Employee Bonus List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Department') <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select department_id">
                                <option value=""> -- Select -- </option>
                                @foreach ($departments as $department)
                                    <option data-employees="{{ $department->employees }}" value="{{ $department->id }}" {{ $department->id == request('department_id') ? 'selected' : '' }}>
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize ">@lang('Employee') <span
                                    class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select employee_id">
                                <option value=""> -- Select -- </option>
                                
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
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" required>
                        </div>
                    </div>
                    <div class="col-12  col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Note') <span
                                    class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <input type="submit" class="btn btn-primary float-end"
                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                        value="@lang('Submit')">
                </div>
            </div>
        </div>
    </form>
@endsection


@push('script')
    <script>
        $('[name=department_id]').on('change', function() {
            var employees = $(this).find('option:selected').data('employees');
            var option = '<option value="">Select Employee</option>';
            $.each(employees, function(index, value) {
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    value.name + "</option>";
            });

            $('select[name=employee_id]').html(option);
        }).change();
    </script>
@endpush

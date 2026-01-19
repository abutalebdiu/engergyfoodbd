@extends('admin.layouts.app',['title'=>'Add New Employee'])
@section('panel')

          <!--breadcrumb-->
          <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
            <div>
                <h6 class="m-0">Add New Employee</h6>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.employee.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-list"></i> Employee List</a>
            </div>
        </div>
        <!--breadcrumb-->
        <form action="{{ route('admin.employee.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Employee ID')</label>
                                <input class="form-control" type="text" name="emp_id"
                                    value="{{ old('emp_id') }}">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Name') <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Designation')</label>
                                <input class="form-control" type="text" name="designation"
                                    value="{{ old('designation') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Department') <span class="text-danger">*</span></label>
                                 <select name="department_id" id="department_id" class="form-select" required>
                                    <option value=""> -- Select -- </option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                 </select>
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Email') </label>
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Mobile Number') </label>
                                <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Father')</label>
                                <input class="form-control" type="text" name="father" value="{{ old('father') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Mother')</label>
                                <input class="form-control" type="mother" name="mother" value="{{ old('mother') }}">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('NID No')</label>
                                <input class="form-control" type="number" name="nid" value="{{ old('nid') }}">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Date of Birth')</label>
                                <input class="form-control" type="date" name="dob" value="{{ old('dob') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Address')</label>
                                <input class="form-control" type="text" name="address" value="{{ old('address') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="{{ old('city') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Emergency Contact No')</label>
                                <input class="form-control" type="text" name="emergency_contact"
                                    value="{{ old('emergency_contact') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Emergency Contact Person name')</label>
                                <input class="form-control" type="text" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name') }}">
                            </div>
                        </div>

                        <div class="pb-3 col-md-12">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Education')</label>
                                <textarea class="form-control" name="education" value="">{{ old('education') }}</textarea>
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Join Date')</label>
                                <input class="form-control" type="date" name="joindate"
                                    value="{{ old('joindate') }}">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Position')</label>
                                <input class="form-control" type="text" name="position" value="1">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Food Allowance')</label>
                                <input class="form-control" type="text" name="food_allowance" value="0">
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('Bonus Eligibility')</label>
                                 <select name="bonus_eligibility" id="bonus_eligibility" class="form-select bonus_eligibility">
                                    <option {{ old('bonus_eligibility') == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
                                    <option {{ old('bonus_eligibility') == "No" ? "selected" : "" }} value="No">No</option>
                                 </select>
                            </div>
                        </div>
                        <div class="pb-3 col-md-4">
                            <div class="form-group ">
                                <label class="form-label text-capitalize">@lang('CV Attachment')</label>
                                <input class="form-control" type="file" name="attachment"
                                    value="{{ old('attachment') }}">
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

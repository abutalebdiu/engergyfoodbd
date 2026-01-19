@extends('admin.layouts.app', ['title' => 'Add New Salary Payment'])
@section('panel')
    <form action="{{ route('admin.salarypayment.single.employee.salary.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Salary Payment <a href="{{ route('admin.salarypaymenthistory.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Salary Payment History
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-select" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option data-accounts="{{ $method->accounts }}" @selected(old('payment_method_id' == @$method->id))
                                        value="{{ $method->id }}">
                                        {{ $method->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-select account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('date') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Department') <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select department_id">
                                <option value=""> -- Select -- </option>
                                @foreach ($departments as $department)
                                    <option data-employees="{{ $department->employees }}" value="{{ $department->id }}">
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize ">@lang('Employee') <span
                                    class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select employee_id">
                                <option value=""> -- Select -- </option>

                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="salary_generate_id" id="salary_generate_id"
                                class="form-select salary_generate_id">
                                <option value=""> -- Select -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" required>
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
        $('[name=payment_method_id]').on('change', function() {
            var accounts = $(this).find('option:selected').data('accounts');
            var option = '<option value="">Select Account</option>';
            $.each(accounts, function(index, value) {
                var name = value.title;
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();
    </script>
@endpush

@push('script')
    <script>
        // $('[name=employee_id]').on('change', function() {
        //     var unpaidsalary = $(this).find('option:selected').data('unpaidsalary');
        //     var option = '<option value="">-- Select --</option>';
        //     $.each(unpaidsalary, function(index, value) {
        //         var month = value.month.name;
        //         var year = value.year.name;
        //         var amount = value.payable_amount;
        //         option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
        //             month + "-" + year + " (" + amount + ")" + "</option>";
        //     });

        //     $('select[name=salary_generate_id]').html(option);
        // }).change();

        $("#employee_id").on("change", function() {
            var employee = $(this).val();
            var option = "";

            $.ajax({
                type: "GET",
                url: "{{ route('admin.hr.get_unpaid_salary') }}",
                data: {
                    employee: employee
                },
                success: function(response) {
                    $.each(response, function(index, value) {
                        var selected = (value.id == employee) ? "selected" : "";
                        option += "<option value='" + value.id + "' " + selected + ">" + value
                            .month + "</option>";
                    });

                    $("#salary_generate_id").html(option);
                }
            });
        });
    </script>

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

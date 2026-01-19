@extends('admin.layouts.app', ['title' => 'Add New Employee Over Time Allowance'])
@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Add New Employee Over Time Allowance</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.overtimeallowance.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-list"></i> @lang('Employee Over Time Allowance')</a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">
            <form action="" method="get">
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option
                                        @if (isset($department_id)) {{ $department_id == $department->id ? 'selected' : '' }} @endif
                                        value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button class="btn btn-primary" name="search">
                            <i class="fa fa-search"></i> @lang('Search')
                        </button>
                    </div>
                </div>
            </form>
            @if ($searching == 'Yes')
                <form action="{{ route('admin.overtimeallowance.store') }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('SL No') </th>
                                    <th>@lang('EMP ID') </th>
                                    <th>@lang('Name') </th>
                                    <th>@lang('Daily Salary') </th>
                                    <th>@lang('Amount') </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $index => $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->emp_id }} </td>
                                        <td style="text-align: left;padding-left:5px"> {{ $item->name }} </td>
                                        <td style="text-align: right;padding-right:20px"> {{ en2bn($item->daily_salary) }}
                                            <input type="hidden" name="employee_id[]" value="{{ $item->id }}">

                                        </td>
                                        <td><input type="text" name="amount[]" class="form-control amount-field"
                                                size="2" onkeyup="calculateTotal()"
                                                value="{{ $item->daily_salary }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">@lang('Total')</th>
                                    <td>
                                        <span id="totalvalue">0</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12 border-bottom">
                            <h5 class="mb-0 mt-4">Payment</h5>
                        </div>
                        <div class="col-12 offset-md-3 col-md-3">
                            <div class="form-group py-2">
                                <label class="form-label">@lang('Account')</label>
                                <input class="form-control" type="date" name="date"
                                    value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group py-2">
                                <label for="" class="form-label">Payment Method</label>
                                <select name="payment_method_id" id="payment_method_id" class="form-control">
                                    <option value="">Select Method</option>
                                    @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                        <option data-accounts="{{ $method->accounts }}" @selected(old('payment_method_id' == @$method->id))
                                            value="{{ $method->id }}">
                                            {{ $method->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group py-2">
                                <label class="form-label">@lang('Account')</label>
                                <select name="account_id" id="account_id" class="form-control account_id">
                                    <option value="">@lang('Select Account')</option>
                                </select>
                            </div>
                        </div>



                        <div class="col-12">
                            <a href="{{ route('admin.expense.index') }}" class="btn btn-outline-info float-start">Back</a>
                            <input type="submit" class="btn btn-primary float-end"
                                onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                value="@lang('Submit')">
                        </div>
                    </div>
                </form>
        </div>
        @endif
    </div>



@endsection


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the quantity inputs
            const qtyInputs = document.querySelectorAll('input[name="amount[]"]');

            // Add event listeners to each input
            qtyInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    // Prevent form submission on Enter key press and move to the next input
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to next quantity input on Down arrow key press
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to previous quantity input on Up arrow key press
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevInput = qtyInputs[index - 1];
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                });
            });
        });
    </script>
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

    <script>
        function calculateTotal() {
            let total = 0;

            // Select all amount fields
            document.querySelectorAll('.amount-field').forEach(field => {
                let value = parseFloat(field.value.replace(/,/g, '')) || 0; // Parse number, ignore commas
                total += value;
            });

            // Update total value in the span
            document.getElementById('totalvalue').textContent = total.toLocaleString('en-US'); // Format number
        }

        // Initialize total on page load
        window.onload = calculateTotal;
    </script>
@endpush

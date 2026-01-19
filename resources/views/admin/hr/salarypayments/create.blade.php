@extends('admin.layouts.app', ['title' => 'Add New Salary Payment'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Add New Salary Payment <a href="{{ route('admin.salarypaymenthistory.index') }}"
                    class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Salary Payment History
                    List</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Department') <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select department_id">
                                <option value=""> -- Select -- </option>
                                @foreach ($departments as $department)
                                    <option {{ request()->department_id == $department->id ? 'selected' : '' }}
                                        value="{{ $department->id }}">
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Year') <span
                                    class="text-danger">*</span></label>
                            <select name="year_id" id="year_id" class="form-select year_id">
                                @foreach ($years as $year)
                                    <option {{ request()->year_id == $year->id ? 'selected' : '' }}
                                        value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                                <option value=""> -- Select -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-select month_id">
                                @foreach ($months as $item)
                                    <option {{ request()->month_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                                <option value=""> -- Select -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 py-2">
                        <button class="btn btn-primary mt-4" name="search">
                            <i class="fa fa-search"></i> @lang('Search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    @if ($searching == 'Yes')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.salarypaymenthistory.store') }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>@lang('SL No') </th>
                                    <th>@lang('EMP ID') </th>
                                    <th>@lang('Name') </th>
                                    <th>@lang('Amount') </th>
                                    <th>@lang('Pay') </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salarygenerates as $index => $item)
                                    <tr>
                                        <td><input type="checkbox" value="{{ $index }}" name="sal_pay_id[]"></td>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ optional($item->employee)->emp_id }} </td>
                                        <td style="text-align: left;padding-left:5px">
                                            {{ optional($item->employee)->name }} </td>
                                        <td style="text-align: right;padding-right:20px">
                                            {{ en2bn($item->payable_amount) }}
                                            <input type="hidden" name="salary_generate_id[]" value="{{ $item->id }}">
                                            <input type="hidden" name="employee_id[]" value="{{ $item->employee_id }}">

                                        </td>
                                        <td><input type="text" name="amount[]" class="form-control" size="2"
                                                value="{{ en2bn($item->payable_amount) }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <th colspan="4">@lang('Total')</th>
                                <th>{{ en2bn($salarygenerates->sum('payable_amount')) }}</th>
                                <th></th>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-12 border-bottom">
                            <h5 class="mb-0 mt-4">Payment</h5>
                        </div>
                        <div class="col-12 offset-md-3 col-md-3">
                            <div class="form-group py-2">
                                <label class="form-label">@lang('Date')</label>
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
                            <a href="{{ route('admin.salarypaymenthistory.index') }}" class="btn btn-outline-info float-start">Back</a>
                            <input type="submit" class="btn btn-primary float-end"
                                onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                value="@lang('Submit')">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif


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

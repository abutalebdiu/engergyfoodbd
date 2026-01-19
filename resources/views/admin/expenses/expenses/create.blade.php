@extends('admin.layouts.app', ['title' => 'Add New Employee'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.expense.index') }}" class="btn btn-sm btn-outline-primary">Expense List
            </a>
        </div>
    @endpush

    <form action="{{ route('admin.expense.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"> Add New Expense </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('voucher no')</label>
                            <input class="form-control" type="text" name="voucher_no" required
                                value="{{ old('voucher_no') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select select2" name="category_id">
                            <option value="">Select Category</option>
                            @foreach ($expensecategories as $category)
                                <option {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Expense By</label>
                        <select class="form-select select2" name="expense_by">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option {{ old('expense_by') == $employee->id ? 'selected' : '' }}
                                    value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pb-3 col-md-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Expense Date')</label>
                            <input type="date" class="form-control" name="expense_date"
                                value="{{ old('expense_date') ? old('expense_date') : date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12 offset-md-6 col-md-3">
                        <div class="form-group">
                            <label for="">Item</label>
                            <input type="text" name="name[]" value="" class="form-control name" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="">Qty</label>
                            <input type="text" name="qty[]" value="" onkeypress="return validateNumber(event)"
                                class="form-control qty" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="text" name="amount[]" value="" onkeypress="return validateNumber(event)"
                                class="form-control amount" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 text-center addnewrow">
                        <i class="bi bi-plus btn btn-primary btn-sm text-white mt-4"></i>
                    </div>
                </div>

                <div class="showmorerow"></div>

                <div class="row mt-2">
                    <div class="col-12 col-md-2 offset-md-9">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Total Amount</label>
                            <input type="text" name="total_amount" onkeypress="return validateNumber(event)"
                                value="{{ old('total_amount') }}" id="sumResult" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Type here........">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row">
                        <div class="col-12 offset-md-3 col-md-3">
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
                        <div class="col-12 col-md-2">
                            <div class="form-group py-2">
                                <label for="" class="form-label">Total Amount</label>
                                <input type="text" name="pay_amount" onkeypress="return validateNumber(event)"
                                    value="{{ old('pay_amount') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-1"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.expense.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <input type="submit" class="btn btn-primary float-end"
                            onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                            value="@lang('Submit')">
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('script')
        <script>
            $(document).ready(function() {
                var AddButton = $('.addnewrow');
                var wrapper = $('.showmorerow');
                var FieldHTML = `<div class="row mt-3 parent_remove">
                    <div class="col-12 offset-md-6 col-md-3">
                        <div class="form-group">
                            <input type="text" name="name[]" value="" class="form-control name" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <input type="text" name="qty[]" value="" class="form-control qty" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <input type="text" name="amount[]" value="" class="form-control amount" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-1 text-center">
                        <i class="bi bi-x-lg btn btn-danger btn-sm text-white mt-2 remove_btn"></i>
                    </div>
                </div>`;


                $(AddButton).click(function() {
                    $(wrapper).append(FieldHTML);
                });

                $(wrapper).on('click', '.remove_btn', function(e) {
                    e.preventDefault();
                    $(this).closest('.parent_remove').remove();
                });


                $(document).on("input", ".amount", function() {
                    calculateSum();
                });

                function calculateSum() {
                    // Get all input elements with the class 'input-field'
                    var inputFields = $(".amount");

                    // Calculate sum
                    var sum = 0;
                    inputFields.each(function() {
                        sum += parseFloat($(this).val()) || 0;
                    });

                    // Update the result
                    $("#sumResult").val(sum);
                }
            });
            // js end
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
    @endpush
@endsection
@include('components.select2')

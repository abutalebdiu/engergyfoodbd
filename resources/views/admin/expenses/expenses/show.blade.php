@extends('admin.layouts.app', ['title' => 'Show Expense'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.expense.index') }}" class="btn btn-sm btn-outline-primary">Expense List
            </a>
        </div>
    @endpush

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Expense Detail
                <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                    data-bs-target="#ExpensePayment">
                    <i class="fa fa-money-bill"></i> Pay Payment
                </a>
            </h5>


            <!-- Modal -->
            <div class="modal fade" id="ExpensePayment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.expensepaymenthistory.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Pay Expense Payment
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Expense By: </label>
                                            <input type="hidden" name=""
                                                value="{{ optional($expense->expenseby)->name }}">
                                            <input type="hidden" name="expense_id" value="{{ $expense->id }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Payment
                                                Method</label>
                                            <select name="payment_method_id" id="payment_method_id"
                                                class="form-control payment_method_id" required>
                                                <option value="">Select Method</option>
                                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                                    <option data-accounts="{{ $method->accounts }}"
                                                        @selected(old('payment_method_id' == @$method->id)) value="{{ $method->id }}">
                                                        {{ $method->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label class="form-label text-start">@lang('Account')</label>
                                            <select name="account_id" id="account_id" class="form-control account_id"
                                                required>
                                                <option value="">@lang('Select Account')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Total Amount</label>
                                            <input type="text" value="{{ number_format($expense->total_amount) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Paid Amount</label>
                                            <input type="text"
                                                value="{{ number_format($expense->paidamount($expense->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Unpaid
                                                Amount</label>
                                            <input type="text"
                                                value="{{ number_format($expense->total_amount - $expense->paidamount($expense->id)) }}"
                                                class="form-control text-danger" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Pay Amount</label>
                                            <input type="text" value="" name="amount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Date</label>
                                            <input type="date" value="{{ Date('Y-m-d') }}" name="date"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary float-end"
                                    onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                    value="@lang('Submit')">
                            </div>
                        </div>
                    </form>
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
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <p>
                        Voucher No: {{ $expense->voucher_no }} <br>
                        Date: {{ $expense->expense_date }} <br>
                        Category : {{ optional($expense->category)->name }} <br>
                        Total Amount : {{ number_format($expense->total_amount, 2) }} <br>
                        Paid Amount : {{ number_format($expense->paidamount($expense->id), 2) }} <br>
                        Due Amount : {{ number_format($expense->total_amount - $expense->paidamount($expense->id), 2) }}
                        <br>
                        Payment Status : <span
                            class="btn btn-{{ statusButton($expense->status) }} btn-sm">{{ $expense->status }}</span>
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <p>Expense By : {{ optional($expense->expenseby)->name }} <br></p>
                    <p>Note :
                        {{ $expense->note }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-12">
                    <h5>Item Detail</h5>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>@lang('SL No')</th>
                                <th>@lang('Item')</th>
                                <th>@lang('qty')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expense->expensedetail as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ en2bn($detail->qty) }}</td>
                                    <td>{{ en2bn(number_format($detail->amount, 2, '.', ',')) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <td>{{ en2bn(number_format($expense->total_amount, 2, '.', ',')) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <h5>Payment History</h5>
                </div>
                <div class="col-12 col-md-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                <th>@lang('Invoice No')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Expense Voucher')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Account')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expense->expensepayment as $item)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $item->ex_invoice_no }}</td>
                                    <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                    <td> {{ optional($item->expense)->voucher_no }}</td>
                                    <td> {{ optional($item->paymentmethod)->name }}</td>
                                    <td> {{ optional($item->account)->title }}</td>
                                    <td> {{ en2bn(number_format($item->amount, 2)) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </div>
    </div>
@endsection

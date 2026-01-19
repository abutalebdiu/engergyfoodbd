@extends('admin.layouts.app', ['title' => __('Commission Invoice Show')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Commission Invoice Show')
                <a href="{{ route('admin.commissioninvoice.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> @lang('Add New')</a>

                <a href="{{ route('admin.commissioninvoice.index') }}" class="btn btn-outline-primary btn-sm float-end me-2">
                    <i class="bi bi-list"></i> @lang('List')</a>

                <a href="#" class="btn btn-sm btn-outline-success float-end me-2" data-bs-toggle="modal"
                    data-bs-target="#CIPayment">
                    <i class="fa fa-money-bill"></i> @lang('Make Payment')
                </a>

                <a href="{{ route('admin.commissioninvoice.invoice.print', $commissioninvoice->id) }}"
                    class="btn btn-outline-primary btn-sm float-end me-2">
                    <i class="las la-print"></i> @lang('Print')
                </a>
            </h6>
        </div>

        <div class="modal fade" id="CIPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.commissioninvoice.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="commission_invoice_id" value="{{ $commissioninvoice->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Commission Invoice Payment')</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Payment Method')</label>
                                        <select name="payment_method_id" id="payment_method_id"
                                            class="form-control payment_method_id" required>
                                            <option value="">@lang('Select Method')</option>
                                            @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                                <option data-accounts="{{ $method->accounts }}" @selected(old('payment_method_id' == @$method->id))
                                                    value="{{ $method->id }}">
                                                    {{ $method->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label class="form-label text-start">@lang('Account')</label>
                                        <select name="account_id" id="account_id" class="form-control account_id" required>
                                            <option value="">@lang('Select Account')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Amount') <span
                                                class="text-danger">*</span> </label>
                                        <input type="text" value="" name="amount" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Date')</label>
                                        <input type="date" value="{{ Date('Y-m-d') }}" name="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Note')</label>
                                        <textarea name="note" id="note" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('Close')</button>
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

        @if (!empty($mergedData) && count($mergedData) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Date')</th>
                            <th>@lang('Order ID')</th>
                            <th>@lang('Previous Due')</th>
                            <th>@lang('Challan Amount')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Return Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Total')</th>
                            <th>@lang('Commission Status')</th>
                            <th>@lang('Due Collection')</th>
                            <th>@lang('Total Due Amount')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalnetchallanamount = 0;
                            $totaldueamount = 0;
                            $returncommissiontotal = 0;
                            $continueDue = 0;
                            $isFirstOrder = true;
                        @endphp

                        @foreach ($mergedData as $key => $item)
                            @if ($item['type'] == 'order')
                                @php
                                    $order = $item['data'];

                                    // প্রথম order থেকে previous_due দিয়ে শুরু
                                    if ($isFirstOrder) {
                                        $continueDue = $order->previous_due;
                                        $isFirstOrder = false;
                                    }

                                    // order related totals
                                    $totalnetchallanamount += $order->sub_total - $order->return_amount;
                                    $totaldueamount +=
                                        $order->sub_total -
                                        $order->return_amount -
                                        $order->paid_amount -
                                        $order->commission;
                                    $returncommissiontotal += $order->orderreturn->sum('commission');

                                    // current order due = grand total - paid_amount
                                    $currentOrderDue = $order->grand_total - $order->paid_amount;

                                    // running continue due
                                    $continueDue += $currentOrderDue;
                                @endphp

                                <tr>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($order->date))) }}</td>
                                    <td>
                                        #{{ $order->oid }}
                                        <input type="hidden" name="order_id[]" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ en2bn(number_format($order->previous_due, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->commission ?? 0, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->grand_total, 2, '.', ',')) }}
                                    </td>
                                    <td>{{ en2bn(number_format($order->paid_amount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($order->grand_total - $order->paid_amount, 2, '.', ',')) }}
                                    </td>

                                    <td>{{ $order->commission_status }}</td>
                                    <td></td>

                                    <td class="fw-bold text-danger">
                                        {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                                </tr>
                            @else
                                @php
                                    $payment = $item['data'];

                                    // payment দিলে continue due থেকে minus
                                    $continueDue -= $payment->amount;
                                @endphp

                                <tr class="table-info">
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($payment->date))) }}</td>
                                    <td colspan="4">
                                        <span class="badge bg-success">@lang('Due Payment')</span>
                                    </td>
                                    <td colspan="5">@lang('Customer Due Payment')</td>
                                    <td colspan="2"> {{ $payment->created_at }} </td>
                                    <td><strong>{{ en2bn(number_format($payment->amount, 2, '.', ',')) }}</strong>
                                    </td>

                                    <td class="fw-bold text-danger">
                                        {{ en2bn(number_format($continueDue, 2, '.', ',')) }}</td>
                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary text-white">
                            <td>@lang('Total')</td>
                            <td></td>
                            <td></td>
                            <td>{{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                            </td>
                            <input type="hidden" name="return_amount" value="{{ $orders->sum('return_amount') }}">
                            <input type="hidden" name="net_amount" value="{{ $orders->sum('net_amount') }}">
                            <input type="hidden" name="paid_amount" value="{{ $orders->sum('paid_amount') }}">
                            <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($orders->sum('grand_total') - $orders->sum('paid_amount'), 2, '.', ',')) }}
                            </td>
                            <td></td>
                            <td>{{ en2bn(number_format($commissioninvoice->customer_due_payment, 2, '.', ',')) }}
                            </td>
                            <td></td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <div class="summery" style="width: 45%;padding:10px">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="text-align:left">@lang('Total Order Amount')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Current Month Commission')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('After Commission Total')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Total Order Paid Amount')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}</td>
                    </tr>

                    <tr>
                        <th style="text-align:left">@lang('Current Month Due')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Last Month Due')</th>
                        <td style="text-align:right;padding-right:10px">
                            @if ($last_month_due->count() > 0)
                                {{ en2bn(number_format($last_month_due->sum('amount'), 2, '.', ',')) }}
                            @else
                                {{ en2bn(number_format($commissioninvoice->customer?->opening_due, 2, '.', ',')) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Customer Due Payment')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($commissioninvoice->customer_due_payment, 2, '.', ',')) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align:left">@lang('Total Company/Customer Receivable')</th>
                        <td style="text-align:right;padding-right:10px">
                            {{ en2bn(number_format($commissioninvoice->amount, 2, '.', ',')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>



    </div>


    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">
                Commission Invoice Payment
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Entry User')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissioninvoicepayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }}</td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }}</td>
                                <td> {{ $item->note }}</td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">@lang('Total')</th>
                            <th>{{ en2bn(number_format($commissioninvoicepayments->sum('amount'), 2, '.', ',')) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

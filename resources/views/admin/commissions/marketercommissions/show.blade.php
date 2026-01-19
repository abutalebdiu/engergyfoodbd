@extends('admin.layouts.app', ['title' => __('Marketer Commission Detail')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Official Loan Show')
                <a href="{{ route('admin.marketercommission.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> @lang('Add New')</a>

                <a href="{{ route('admin.marketercommission.index') }}" class="btn btn-outline-primary btn-sm float-end me-2">
                    <i class="bi bi-list"></i> @lang('List')</a>

                <a href="#" class="btn btn-sm btn-outline-success float-end me-2" data-bs-toggle="modal"
                    data-bs-target="#CIPayment">
                    <i class="fa fa-money-bill"></i> @lang('Make Payment')
                </a>

                <a href="{{ route('admin.marketercommission.invoice.print', $marketercommission->id) }}"
                    class="btn btn-sm btn-outline-info float-end me-2">
                    <i class="las la-print"></i> @lang('Print')
                </a>
            </h6>
        </div>
        <div class="modal fade" id="CIPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.marketercommissionpayment.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="marketer_commission_id" value="{{ $marketercommission->id }}">
                    <input type="hidden" name="marketer_id" value="{{ $marketercommission->marketer_id }}">
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
        <div class="card-body">
            <h5 class="border-bottom">Marketer Info</h5>
            <p>
                <b>@lang('Name')</b> : {{ $marketercommission->marketer?->name }}, <br>
                <b>@lang('Mobile')</b> : {{ $marketercommission->marketer?->mobile }}, <br>
                <b>@lang('Address')</b> : {{ $marketercommission->marketer?->address }} <br>
                <b>@lang('Commission')</b> : {{ en2bn($marketercommission->marketer?->amount) }}%
            </p>

            @if ($marketercommission->orders)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>

                                <th>@lang('SL No')</th>
                                <th>@lang('OID')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Customer')</th>
                                <th>@lang('qty')</th>
                                <th>@lang('Sub Total')</th>
                                <th>@lang('Return Amount')</th>
                                <th>@lang('Net Amount')</th>
                                <th>@lang('Commission')</th>
                                <th>@lang('Grand Total')</th>
                                <th>@lang('Paid Amount')</th>
                                <th>@lang('Due Amount')</th>
                                <th>@lang('Commission Status')</th>
                                <th>@lang('Previous Due')</th>
                                <th>@lang('Total Due Amount')</th>
                                <th>@lang('Marketer Commission')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalqty = 0;
                                $orders = $marketercommission->orders;
                            @endphp
                            @forelse($orders as $item)
                                @php $totalqty += $item->orderdetail->sum('qty'); @endphp
                                <tr>
                                    <td> {{ en2bn($loop->iteration) }} </td>
                                    <td><a href="{{ route('admin.order.show', $item->id) }}">
                                            {{ $item->oid }} </a> </td>
                                    <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                    <td class="text-start"> <a
                                            href="{{ route('admin.customers.statement', $item->customer_id) }}">
                                            {{ optional($item->customer)->name }}</a></td>
                                    <td> {{ en2bn($item->orderdetail->sum('qty')) }}</td>
                                    <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->return_amount, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->paid_amount, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->order_due, 2, '.', ',')) }}</td>
                                    <td> <span
                                            class="btn btn-{{ statusButton($item->commission_status) }} btn-sm">{{ $item->commission_status }}</span>
                                    </td>
                                    <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                                    <td> {{ en2bn(number_format($item->marketer_commission, 2, '.', ',')) }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th>@lang('Total')</th>
                                <th>{{ en2bn($totalqty) }}</th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                                </th>


                                <th>
                                    {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}
                                </th>
                                <th>
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('previous_due'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('customer_due'), 2, '.', ',')) }}
                                </th>
                                <th>
                                    {{ en2bn(number_format($orders->sum('marketer_commission'), 2, '.', ',')) }}
                                </th>

                            </tr>
                        </tfoot>
                    </table><!-- table end -->
                </div>
            @endif


            <div style="width:40%">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th style="text-align: left">Customers Previous Due Amount</th>
                            <td>{{ en2bn(number_format($marketercommission->previous_due, 2, '.', ',')) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Total Net Amount</th>
                            <td>{{ en2bn(number_format($marketercommission->net_amount, 2, '.', ',')) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Total Paid Amount</th>
                            <td>{{ en2bn(number_format($marketercommission->paid_amount, 2, '.', ',')) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Total Customer Due Payment</th>
                            <td>{{ en2bn(number_format($marketercommission->customer_due_payment, 2, '.', ',')) }}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Total Due</th>
                            <td>{{ en2bn(number_format($marketercommission->total_due_amount, 2, '.', ',')) }}
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Marketer Commission</th>
                            <td>{{ en2bn(number_format($marketercommission->payable_amount, 2, '.', ',')) }}
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Overall Due</th>
                            <td style="text-align: right;padding-left:10px">
                                {{ en2bn(number_format($marketercommission->total_due_amount - $marketercommission->payable_amount, 2, '.', ',')) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">
                Marketer Commission Payment
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Marketer')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Entry User')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($marketercommissionpayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }}</td>
                                <td> {{ optional($item->marketer)->name }} </td>
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
                            <th colspan="5">@lang('Total')</th>
                            <th>{{ en2bn(number_format($marketercommissionpayments->sum('amount'), 2, '.', ',')) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

@extends('admin.layouts.app', ['title' => 'Order Detail'])
@push('style')
    <style>
        table,
        td,
        th {
            padding: 2px !important;
        }
    </style>
@endpush
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Order Detail')
                <a href="{{ route('admin.order.invoice.print', $order->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Invoice Print</a>

                <a href="{{ route('admin.order.challan.print', $order->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Challan Print</a>

                <a href="{{ route('admin.order.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-list"></i> @lang('Order List')</a>

                <a href="#" class="btn btn-sm btn-outline-success float-end ms-2" data-bs-toggle="modal"
                    data-bs-target="#order_payment">
                    <i class="fa fa-money-bill"></i> @lang('Receive Payment')
                </a>
                
                @if (Auth::guard('admin')->user()->hasPermission('admin.orderreturn.create'))
                <a href="{{ route('admin.orderreturn.create') }}?order_id={{ $order->id }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-undo" aria-hidden="true"></i> @lang('New Order Return')
                </a>
                @endif

                <a href="{{ route('admin.order.pos.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-undo" aria-hidden="true"></i> @lang('New Order')
                </a>

                <a href="{{ route('admin.quotation.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-list"></i>@lang('Quotation List')
                </a>

            </h6> <!-- Modal -->
            <div class="modal fade" id="order_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.orderpayment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Sales Order Payment Receive')</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">
                                                @lang('Customer'):
                                                {{ $order->customer?->name }}
                                            </label>
                                            <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Payment Method')</label>
                                            <select name="payment_method_id" id="payment_method_id"
                                                class="form-control payment_method_id" required>
                                                <option value="">@lang('Select Method')</option>
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
                                            <label for="" class="form-label">@lang('Total Due')</label>
                                            <input type="text" value="{{ en2bn($order->customer_due) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Pay Amount') <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" value="" name="amount" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Date')</label>
                                            <input type="date" @if($order) value="{{ $order->date }}"  @else  value="{{ Date('Y-m-d') }}" @endif name="date"
                                                class="form-control">
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


        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Order Info</h5>
                    <p>
                        <b>Order No</b> : {{ $order->oid }}, <br>
                        <b>@lang('Date')</b> : {{ en2bn(Date('d-m-Y', strtotime($order->date))) }}, <br>
                        <b>@lang('Due Amount')</b> : {{ en2bn(number_format($order->customer_due, 2, '.', ',')) }}
                        <br>
                        <b>@lang('Payment Status')</b> : <span
                            class="btn btn-{{ statusButton($order->payment_status) }} btn-sm">{{ $order->payment_status }}</span>
                        <br>

                        <b>@lang('Marketer'):</b> {{ optional($order->marketer)->name }} <br>
                        <b>@lang('Marketer Commission'): </b>{{ en2bn($order->marketer_commission) }}

                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Customer Info</h5>
                    <p>
                        <b>@lang('ID')</b> : {{ optional($order->customer)->uid }}, <br>
                        <b>@lang('Name')</b> : {{ optional($order->customer)->name }}, <br>
                        <b>@lang('Mobile')</b> : {{ optional($order->customer)->mobile }}, <br>
                        <b>@lang('Address')</b> : {{ optional($order->customer)->address }} <br>
                        <b>@lang('Commission Type')</b> : {{ __(optional($order->customer)->commission_type) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Product Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@lang('SL')</th>
                        <th>@lang('Product')</th>
                        <th>@lang('Weight')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Quantity')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Net Amount')</th>
                        <th>@lang('Commission')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderdetail as $odetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align:left">{{ optional($odetail->product)->name }}</td>
                            <td>{{ optional($odetail->product)->weight }}</td>
                            <td>{{ en2bn($odetail->price) }}</td>
                            <td>{{ en2bn($odetail->qty) }}</td>
                            <td>{{ en2bn(number_format($odetail->amount, 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($odetail->amount, 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($odetail->product_commission, 2, '.', ',')) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark">
                        <td></td>
                        <td></td>
                        <td class="text-white"> @lang('Total')</td>
                        <td class="text-white"> </td>
                        <td class="text-white">{{ en2bn($order->orderdetail->sum('qty')) }}</td>
                        <td class="text-white">{{ en2bn(number_format($order->orderdetail->sum('amount'), 2, '.', ',')) }}
                        </td>
                        <td class="text-white">{{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                        <td class="text-white">
                            {{ en2bn(number_format($order->orderdetail->sum('product_commission'), 2, '.', ',')) }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Sub Total')</th>
                        <td> {{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Return Amount')</th>
                        <td>{{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Net Amount')</th>
                        <td>{{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Commission')  <span style="color:red">(-)</span> </th>
                        <td>{{ en2bn(number_format($order->commission, 2, '.', ',')) }}</td>
                        <td>
                            <span
                                class="btn btn-{{ statusButton($order->commission_status) }} btn-sm">{{ $order->commission_status }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Return Commission') <span style="color:green">(+)</span></th>
                        <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2, '.', ',')) }}</td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Grand Total')</th>
                        <td>{{ en2bn(number_format($order->grand_total, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Previous Due')</th>
                        <td> {{ en2bn(number_format($order->previous_due, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Paid Amount')</th>
                        <td> {{ en2bn(number_format($order->paidamount($order->id), 2, '.', ',')) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Total Due Amount')</th>
                        <td> {{ en2bn(number_format($order->customer_due, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="8">@lang('IN WORD') : {{ $banglanumber }}
                            @lang('Taka Only')</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if ($order->orderreturn->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Order Return')</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL No')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('qty')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Commission')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderreturn as $orderreturn)
                            @foreach ($orderreturn->orderreturndetail as $odetail)
                                @if ($odetail->qty > 0)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($odetail->product)->name }}</td>
                                        <td>{{ en2bn($odetail->price) }}</td>
                                        <td>{{ en2bn($odetail->qty) }}</td>
                                        <td>{{ en2bn(number_format($odetail->amount)) }}</td>
                                        <td>{{ en2bn(number_format($odetail->product_commission)) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>@lang('Total')</td>
                            <td>={{ en2bn($order->orderreturn->sum('totalamount')) }}/-</td>
                            <td>={{ en2bn($order->orderreturn->sum('commission')) }}/-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif


    @if ($order->orderpayments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Payment</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                <th>@lang('Tnx No')</th>
                                <th>@lang('Customer')</th>
                                <th>@lang('Order No')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Account')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Entry By')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderpayments as $item)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $item->tnx_no }} </td>
                                    <td> {{ optional($item->customer)->name }} </td>
                                    <td> {{ optional($item->order)->oid }} </td>
                                    <td> {{ optional($item->paymentmethod)->name }}</td>
                                    <td> {{ optional($item->account)->title }}</td>
                                    <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }} </td>
                                    <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                    <td>{{ optional($item->entryuser)->name }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>
                                <th>@lang('Grand Total')</th>
                                <th>{{ en2bn(number_format($order->orderpayments->sum('amount'), 2, '.', ',')) }}</th>

                                <th></th>
                            </tr>
                        </tfoot>
                    </table><!-- table end -->
                </div>
            </div>
        </div>
    @endif


    <x-destroy-confirmation-modal />
@endsection

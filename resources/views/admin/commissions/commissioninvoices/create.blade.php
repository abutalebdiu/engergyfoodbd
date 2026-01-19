@extends('admin.layouts.app', ['title' => 'Commission invoice generate for Customer'])
@section('panel')

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Commission invoice generate for Customer')
                <a href="{{ route('admin.commissioninvoice.index') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-list"></i> @lang('List')</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.commissioninvoice.store') }}" method="post">
                @csrf
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <select name="customer_id" id="customer_id" class="form-select select2">
                                <option value="">@lang('Search Customer')</option>
                                @foreach ($customers as $customer)
                                    <option
                                        @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                        value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input class="form-control" type="date" name="start_date"
                                    value="{{ request()->start_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input class="form-control" type="date" name="end_date"
                                    value="{{ request()->end_date ?? date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-2">
                            <button class="btn btn-primary " type="submit"> <i class="fa fa-check"></i>
                                @lang('Make Proccess')
                            </button>
                        </div>
                    </div>
                </div>
            </form>


            <form action="{{ route('admin.commissioninvoice.create') }}" method="GET">
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <select name="customer_id" id="customer_id" class="form-select select2">
                                <option value="">@lang('Search Customer')</option>
                                @foreach ($customers as $customer)
                                    <option
                                        @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                        value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">

                                <input class="form-control" type="date" name="start_date"
                                    value="{{ request()->start_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">

                                <input class="form-control" type="date" name="end_date"
                                    value="{{ request()->end_date ?? date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <button class="btn btn-primary" name="search" type="submit"> <i class="fa fa-search"></i>
                                @lang('Search')</button>
                            <button class="btn btn-info" name="pdf" type="submit"> <i class="fa fa-download"></i>
                                @lang('PDF')</button>
                        </div>
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
                <form action="{{ route('admin.commissioninvoice.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="customer_id"
                        @if (isset($customer_id)) value="{{ $customer_id }}" @endif>
                    <div class="row">
                        <div class="pb-3 col-12 col-md-12">
                            <h5 class="border-bottom">Customer Info</h5>
                            <p>
                                <b>@lang('ID')</b> : {{ en2bn($findcustomer->uid) }}, <br>
                                <b>@lang('Name')</b> : {{ $findcustomer->name }}, <br>
                                <b>@lang('Mobile')</b> : {{ $findcustomer->mobile }}, <br>
                                <b>@lang('Address')</b> : {{ $findcustomer->address }} <br>
                                <b>@lang('Commission Type')</b> : {{ __($findcustomer->commission_type) }}
                            </p>


                            {{-- @if (!empty($mergedData) && count($mergedData) > 0)
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
                                                <th>@lang('Continue Dues')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @php
                                                $totalnetchallanamount = 0;
                                                $totaldueamount = 0;
                                                $returncommissiontotal = 0;

                                                $continuedue = 0;
                                            @endphp
                                            @foreach ($mergedData as $key => $item)
                                                @if ($item['type'] == 'order')
                                                    @php
                                                        $order = $item['data'];
                                                        $totalnetchallanamount +=
                                                            $order->sub_total - $order->return_amount;
                                                        $totaldueamount +=
                                                            $order->sub_total -
                                                            $order->return_amount -
                                                            $order->paid_amount -
                                                            $order->commission;
                                                        $returncommissiontotal += $order->orderreturn->sum(
                                                            'commission',
                                                        );

                                                    @endphp

                                                    <tr>
                                                        <td> {{ en2bn(Date('d-m-Y', strtotime($order->date))) }} </td>
                                                        <td>
                                                            #{{ $order->oid }}
                                                            <input type="hidden" name="order_id[]"
                                                                value="{{ $order->id }}">
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->previous_due, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->commission ?? 0, 2, '.', ',')) }}
                                                        </td>
                                                        <td>{{ en2bn(number_format($order->orderreturn->sum('commission'), 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->grand_total, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->paid_amount, 2, '.', ',')) }}
                                                        </td>
                                                        <td> {{ en2bn(number_format($order->order_due, 2, '.', ',')) }}
                                                        </td>
                                                        <td>
                                                            {{ $order->commission_status }}
                                                        </td>
                                                        <td></td>

                                                        <td>{{ en2bn(number_format($order->customer_due ?? 0, 2, '.', ',')) }}
                                                        </td>
                                                        <td>
                                                            <!-- // need to show continue due -->
                                                        </td>
                                                    </tr>
                                                @else
                                                    @php
                                                        $payment = $item['data'];
                                                    @endphp
                                                    <tr class="table-info">
                                                        <td>{{ en2bn(Date('d-m-Y', strtotime($payment->date))) }}</td>
                                                        <td colspan="4"><span
                                                                class="badge bg-success">@lang('Due Payment')</span></td>
                                                        <td colspan="7">@lang('Customer Due Payment')</td>
                                                        <td><strong>{{ en2bn(number_format($payment->amount, 2, '.', ',')) }}</strong>
                                                        </td>
                                                        <td colspan="2"></td>
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
                                                <input type="hidden" name="return_amount"
                                                    value="{{ $orders->sum('return_amount') }}">
                                                <input type="hidden" name="net_amount"
                                                    value="{{ $orders->sum('net_amount') }}">
                                                <input type="hidden" name="paid_amount"
                                                    value="{{ $orders->sum('paid_amount') }}">
                                                <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                                                <td>{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                                                <td></td>
                                                <td>{{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}
                                                </td>

                                                <td></td>
                                                <td>
                                                    <!-- // new to show total continue due -->
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif --}}

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
                                                        $totalnetchallanamount +=
                                                            $order->sub_total - $order->return_amount;
                                                        $totaldueamount +=
                                                            $order->sub_total -
                                                            $order->return_amount -
                                                            $order->paid_amount -
                                                            $order->commission;
                                                        $returncommissiontotal += $order->orderreturn->sum(
                                                            'commission',
                                                        );

                                                        // current order due = grand total - paid_amount
                                                        $currentOrderDue = $order->grand_total - $order->paid_amount;

                                                        // running continue due
                                                        $continueDue += $currentOrderDue;
                                                    @endphp

                                                    <tr>
                                                        <td>{{ en2bn(Date('d-m-Y', strtotime($order->date))) }}</td>
                                                        <td>
                                                            #{{ $order->oid }}
                                                            <input type="hidden" name="order_id[]"
                                                                value="{{ $order->id }}">
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
                                                <input type="hidden" name="return_amount"
                                                    value="{{ $orders->sum('return_amount') }}">
                                                <input type="hidden" name="net_amount"
                                                    value="{{ $orders->sum('net_amount') }}">
                                                <input type="hidden" name="paid_amount"
                                                    value="{{ $orders->sum('paid_amount') }}">
                                                <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                                                <td>{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                                                <td></td>
                                                <td>{{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}
                                                </td>
                                                <td></td>
                                                
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 ">
                            <table class="table table-bordered mb-3">
                                <tbody>
                                    <tr>
                                        <th>@lang('Total Order Amount')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}</td>
                                        <input type="hidden" name="order_amount"
                                            value="{{ $orders->sum('net_amount') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Current Month Commission')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}</td>
                                        <input type="hidden" name="commission_amount"
                                            value="{{ $orders->sum('commission') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Return Commission')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('After Commission Total')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}</td>
                                        <input type="hidden" name="grand_total"
                                            value="{{ $orders->sum('grand_total') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Total Order Paid Amount')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}</td>
                                        <input type="hidden" name="order_amount"
                                            value="{{ $orders->sum('paid_amount') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Current Month Due')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                                        <input type="hidden" name="order_due" value="{{ $orders->sum('order_due') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Last Month Due')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($lastmonthdueamount, 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Due Payment')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }}
                                        </td>
                                        <input type="hidden" name="customer_due_payment"
                                            value="{{ $customerduepayments->sum('amount') }}">
                                    </tr>
                                    <tr>
                                        <th>@lang('Total Company/Customer Receivable')</th>
                                        <td style="text-align:right;padding-right:10px">
                                            {{ en2bn(number_format($last_customer_total_due, 2, '.', ',')) }}</td>
                                        <input type="hidden" name="receivable_amount"
                                            value="{{ $last_customer_total_due }}">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a href="#" class="btn btn-outline-info float-start">@lang('Back')</a>
                            <button type="submit" class="btn btn-primary float-end">@lang('Submit')
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    </form>
@endsection

@include('components.select2')

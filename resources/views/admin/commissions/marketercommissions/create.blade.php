@extends('admin.layouts.app', ['title' => 'Commission invoice generate for Marketer'])
@section('panel')

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Commission invoice generate for Marketer')
                <a href="{{ route('admin.marketercommission.index') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-list"></i> @lang('List')</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.marketercommission.create') }}" method="GET">
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <select name="marketer_id" id="marketer_id" class="form-select select2">
                                <option value="">@lang('Search Marketer')</option>
                                @foreach ($marketers as $marketer)
                                    <option
                                        @if (isset($marketer_id)) {{ $marketer_id == $marketer->id ? 'selected' : '' }} @endif
                                        value="{{ $marketer->id }}"> {{ $marketer->name }}</option>
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
                            <button class="btn btn-primary " type="submit"> <i class="fa fa-search"></i>
                                @lang('Search')</button>

                        </div>
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
                <form action="{{ route('admin.marketercommission.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="marketer_id"
                        @if (isset($marketer_id)) value="{{ $marketer_id }}" @endif>
                    <div class="row">
                        <div class="pb-3 col-12 col-md-12">
                            <h5 class="border-bottom">Marketer Info</h5>
                            <p>

                                <b>@lang('Name')</b> : {{ $findmarketer->name }}, <br>
                                <b>@lang('Mobile')</b> : {{ $findmarketer->mobile }}, <br>
                                <b>@lang('Address')</b> : {{ $findmarketer->address }} <br>
                                <b>@lang('Commission')</b> : {{ en2bn($findmarketer->amount) }}
                            </p>
                            @if (!empty($orders))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>@lang('Date')</th>
                                                <th>@lang('Order ID')</th>
                                                <th>@lang('Customer')</th>
                                                <th>@lang('Challan Amount')</th>
                                                <th>@lang('Return Amount')</th>
                                                <th>@lang('Net Amount')</th>
                                                <th>@lang('Paid Amount')</th>
                                                <th>@lang('Commission')</th>
                                                <th>@lang('Order Due')</th>
                                                <th>@lang('Marketer Commission')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @php
                                                $totalnetchallanamount = 0;
                                                $totaldueamount = 0;
                                            @endphp
                                            @foreach ($orders as $key => $order)
                                                @php
                                                    $totalnetchallanamount += $order->sub_total - $order->return_amount;
                                                    $totaldueamount +=
                                                        $order->sub_total -
                                                        $order->return_amount -
                                                        $order->paid_amount -
                                                        $order->commission;
                                                @endphp

                                                <tr>
                                                    <td> {{ en2bn(Date('d-m-Y', strtotime($order->date))) }} </td>
                                                    <td>
                                                        #{{ $order->oid }}
                                                        <input type="hidden" name="order_id[]"
                                                            value="{{ $order->id }}">
                                                    </td>

                                                    <td class="text-start"> <a
                                                            href="{{ route('admin.customers.statement', $order->customer_id) }}">
                                                            {{ optional($order->customer)->name }}</a></td>

                                                    <td> {{ en2bn(number_format($order->sub_total, 2, '.', ',')) }}</td>
                                                    <td> {{ en2bn(number_format($order->return_amount, 2, '.', ',')) }}
                                                    </td>
                                                    <td> {{ en2bn(number_format($order->net_amount, 2, '.', ',')) }}
                                                    </td>
                                                    <td> {{ en2bn(number_format($order->paid_amount, 2, '.', ',')) }}</td>
                                                    <td> {{ en2bn(number_format($order->commission, 2, '.', ',')) }} </td>
                                                    <td> {{ en2bn(number_format($order->order_due, 2, '.', ',')) }} </td>



                                                    <td> {{ en2bn(number_format($order->marketer_commission ?? 0, 2, '.', ',')) }}
                                                    </td>
                                                </tr>
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
                                                <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                                                </td>
                                                <td>{{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                                                <td>{{ en2bn(number_format($orders->sum('marketer_commission'), 2, '.', ',')) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th>Customers Previous Due Amount</th>
                                        <td>
                                            {{ en2bn(number_format($previousdues, 2, '.', ',')) }}
                                            <input type="hidden" name="previous_due" value="{{ $previousdues }}">
                                            <input type="hidden" name="net_amount" value="{{ $orders->sum('net_amount') }}">
                                            <input type="hidden" name="paid_amount" value="{{ $orders->sum('paid_amount') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Net Amount</th>
                                        <td>{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Paid Amount</th>
                                        <td>{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Customer Due Payment</th>
                                        <td>{{ en2bn(number_format($customerduepayment, 2, '.', ',')) }}
                                            <input type="hidden" name="customer_due_payment" value="{{ $customerduepayment }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Due</th>
                                        <td>
                                            {{ en2bn(number_format(($previousdues + $orders->sum('net_amount')) - ($orders->sum('commission') + $orders->sum('paid_amount') + $customerduepayment), 2, '.', ',')) }}
                                            <input type="hidden" name="total_due_amount"   value="{{ ($previousdues + $orders->sum('net_amount')) - ($orders->sum('commission') + $orders->sum('paid_amount') + $customerduepayment) }}">
                                            <input type="hidden" name="payable_amount"   value="{{ $orders->sum('marketer_commission') }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>This Month Marketer Commission</th>
                                        <td>{{ en2bn(number_format($orders->sum('marketer_commission'), 2, '.', ',')) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Overall Due Amount</th>
                                        <td>{{ en2bn(number_format(($previousdues + $orders->sum('net_amount')) - ($orders->sum('commission') + $orders->sum('paid_amount') + $customerduepayment + $orders->sum('marketer_commission')), 2, '.', ',')) }}</td>
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

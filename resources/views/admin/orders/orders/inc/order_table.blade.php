<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('Action')</th>
                <th>@lang('SL No')</th>
                <th>@lang('OID')</th>
                <th>@lang('Date')</th>
                <th>@lang('Customer')</th>
                <th>@lang('qty')</th>
                <th>@lang('Sub Total')</th>
                <th>@lang('Return Amount')</th>
                <th>@lang('Net Amount')</th>
                <th>@lang('Commission')</th>
                <th>@lang('Return Commission')</th>
                <th>@lang('Grand Total')</th>
                <th>@lang('Paid Amount')</th>
                <th>@lang('Due Amount')</th>
                <th>@lang('Commission Status')</th>
                <th>@lang('Marketer Commission')</th>
                <th>@lang('Previous Due')</th>
                <th>@lang('Total Due Amount')</th>
                <th>@lang('Entry By')</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalqty = 0;
                $returncommissiontotal = 0;
            @endphp
            @forelse($orders as $item)
                @php

                    $totalqty += $item->orderdetail->sum('qty');
                    $countdata = $orders->where('date', $item->date)->where('customer_id', $item->customer_id)->count();

                    $returncommissiontotal += $item->orderreturn->sum('commission');
                @endphp
                <tr @if ($countdata == 2) style="background-color:#fffabd" @endif>
                    <td>
                        <div class="btn-group">
                            <button data-bs-toggle="dropdown">
                                <span class="btn btn-primary btn-sm">Action </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a href="{{ route('admin.order.invoice.print', $item->id) }}">
                                        <i class="fa fa-print"></i> @lang('Invoice Print')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.order.challan.print', $item->id) }}">
                                        <i class="fa fa-print"></i> @lang('Challan Print')
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.order.show', $item->id) }}">
                                        <i class="fa fa-eye"></i> @lang('Show')
                                    </a>
                                </li>
                                @if (Auth::guard('admin')->user()->hasPermission('admin.orderreturn.create'))
                                    <li>
                                        <a href="{{ route('admin.orderreturn.create') }}?order_id={{ $item->id }}">
                                            <i class="fa fa-undo" aria-hidden="true"></i> @lang('Order Return')
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::guard('admin')->user()->hasPermission('admin.order.destroy'))
                                    @if ($item->payment_status != 'Paid' || $item->payment_status != 'Partial')
                                        <li>
                                            <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                data-question="@lang('Are you sure to remove this data from this list?')"
                                                data-action="{{ route('admin.order.destroy', $item->id) }}">
                                                <i class="fa fa-trash-alt"></i> @lang('Remove')
                                            </button>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </td>
                    <td> {{ en2bn($loop->iteration) }} </td>
                    <td><a href="{{ route('admin.order.show', $item->id) }}">
                            {{ $item->oid }} </a> </td>
                    <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                    <td class="text-start"> <a href="{{ route('admin.customers.statement', $item->customer_id) }}">
                            {{ optional($item->customer)->name }}</a></td>
                    <td> {{ en2bn($item->orderdetail->sum('qty')) }}</td>
                    <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->return_amount, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                    <td>{{ en2bn(number_format($item->orderreturn->sum('commission'), 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->paid_amount, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->grand_total - $item->paid_amount, 2, '.', ',')) }}</td>
                    <td> <span
                            class="btn btn-{{ statusButton($item->commission_status) }} btn-sm">{{ $item->commission_status }}</span>
                    </td>
                    <td> {{ en2bn(number_format($item->marketer_commission, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                    <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                    <td> {!! entry_info($item) !!} </td>

                </tr>
            @empty
                <tr>
                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"></th>
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
                    {{ en2bn(number_format($returncommissiontotal, 2, '.', ',')) }}
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
                    {{ en2bn(number_format($orders->sum('marketer_commission'), 2, '.', ',')) }}
                </th>
                <th>
                    {{ en2bn(number_format($orders->sum('previous_due'), 2, '.', ',')) }}
                </th>
                <th>
                    {{ en2bn(number_format($orders->sum('customer_due'), 2, '.', ',')) }}
                </th>

            </tr>
        </tfoot>
    </table><!-- table end -->
</div>

<div class="my-2">
    {{ $orders->appends(request()->except('page'))->links() }}
</div>

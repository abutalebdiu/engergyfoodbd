 <table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th>@lang('SL No')</th>
            <th>@lang('QID')</th>
            <th>@lang('Order')</th>
            <th>@lang('Date')</th>
            <th>@lang('Customer')</th>
            <th>@lang('qty')</th>
            <th>@lang('Sub Total')</th>
            <th>@lang('Net Amount')</th>
            <th>@lang('Commission')</th>
            <th>@lang('Grand Total')</th>
            <th>@lang('Due Amount')</th>
            <th>@lang('Commission Status')</th>
            <th>@lang('Previous Due')</th>
            <th>@lang('Total Due Amount')</th>
            <th>@lang('Print Count')</th>

        </tr>
    </thead>
    <tbody>
        @php $totalqty=0; @endphp
        @forelse($orders as $item)
            @php $totalqty +=$item->quotationdetail->sum('qty');  @endphp
             <tr @if($item->order_id == null && \Carbon\Carbon::parse($item->date)->lt(\Carbon\Carbon::today())) style="background-color:#fbdddd" @endif>

                <td>
                    <div class="btn-group">
                        <button data-bs-toggle="dropdown">
                            <span class="btn btn-primary btn-sm"> @lang('Action')</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('admin.quotation.invoice.print', $item->id) }}">
                                    <i class="fa fa-print"></i> @lang('Invoice Print')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.quotation.challan.print', $item->id) }}">
                                    <i class="fa fa-print"></i> @lang('Challan Print')
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('admin.order.pos.create') }}?quotation_id={{ $item->id }}">
                                    <i class="fa fa-shopping-cart"></i> @lang('Make Order')
                                </a>
                            </li>
                            
                            @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.edit'))
                            <li>
                                <a href="{{ route('admin.quotation.edit', $item->id) }}">
                                    <i class="fa fa-edit"></i> @lang('Edit')
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <a href="{{ route('admin.quotation.show', $item->id) }}">
                                    <i class="fa fa-eye"></i> @lang('Show')
                                </a>
                            </li>
                            
                            @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.destroy'))
                            <li>
                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                    data-action="{{ route('admin.quotation.destroy', $item->id) }}">
                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                </button>
                            </li>
                            @endif
                        </ul>
                    </div>
                </td>
                <td> {{ en2bn($loop->iteration) }} </td>
                <td><a href="{{ route('admin.quotation.show', $item->id) }}">
                        {{ $item->qid }} </a> </td>
                <td> @if($item->order_id) <a href="{{ route('admin.order.show',$item->order_id) }}" target="_blank"> Show</a> @endif</td>
                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }} </td>
                <td class="text-start"> <a href="{{ route('admin.customers.statement', $item->customer_id) }}">
                        {{ optional($item->customer)->name }}</a></td>
                <td> {{ en2bn($item->quotationdetail->sum('qty')) }}</td>
                <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                <td> {{ en2bn(number_format($item->order_due, 2, '.', ',')) }}</td>
                <td> <span  class="btn btn-{{ statusButton($item->commission_status) }} btn-sm">{{ $item->commission_status }}</span> </td>
                <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                <td> {{ en2bn($item->print) }}</td>
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
            <th>@lang('Total')</th>
            <th>
                {{ en2bn($totalqty) }}
            </th>
            <th>
                {{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}
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
            <th></th>
        </tr>
    </tfoot>
</table>


<!-- Pagination -->
<div class="d-flex justify-content-center mt-3">
    {!! $orders->links('pagination::bootstrap-5') !!}
</div>

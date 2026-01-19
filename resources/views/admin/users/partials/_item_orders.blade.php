<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th>@lang('SL No')</th>
            <th>@lang('IID')</th>
            <th>@lang('R. Invoice No')</th>
            <th>@lang('Date')</th>
            <th>@lang('Supplier')</th>
            <th>@lang('Total QTY')</th>
            <th>@lang('Sub Total')</th>
            <th>@lang('Discount')</th>
            <th>@lang('Transport Cost')</th>
            <th>@lang('Labour Cost')</th>
            <th>@lang('Grand Total')</th>
            <th>@lang('Paid Amount')</th>
            <th>@lang('Total Payable')</th>
            <th>@lang('Entry By')</th>
            <th>@lang('Payment Status')</th>
            <th>@lang('Status')</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalqty = 0;
        @endphp

        @forelse($itemorders as $item)
            @php
                $totalqty += $item->itemOrderDetail->sum('qty');
            @endphp
            <tr>
                <td>
                    <div class="btn-group">
                        <button data-bs-toggle="dropdown">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('admin.items.itemOrder.edit', $item->id) }}">
                                    <i class="fa fa-edit"></i> @lang('Edit')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.items.itemOrder.show', $item->id) }}">
                                    <i class="fa fa-eye"></i> @lang('Show')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.itemreturn.create') }}?item_return_id={{ $item->id }}">
                                    <i class="fa fa-undo" aria-hidden="true"></i> @lang('Return')
                                </a>
                            </li>
                            <li>
                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                    data-action="{{ route('admin.items.itemOrder.destroy', $item->id) }}">
                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                </button>
                            </li>
                        </ul>
                    </div>
                </td>
                <td> {{ en2bn($loop->iteration) }} </td>
                <td> <a href="{{ route('admin.items.itemOrder.show', $item->id) }}">{{ $item->iid }}
                    </a>
                </td>
                <td> {{ $item->reference_invoice_no }} </td>
                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                <td class="text-start"> {{ optional($item->supplier)->name }}</td>
                <td> {{ en2bn($item->itemOrderDetail->sum('qty')) }}</td>
                <td> {{ en2bn(number_format($item->subtotal)) }}</td>
                <td> {{ en2bn(number_format($item->discount)) }}</td>
                <td> {{ en2bn(number_format($item->transport_cost)) }}</td>
                <td> {{ en2bn(number_format($item->labour_cost)) }}</td>
                <td> {{ en2bn(number_format($item->totalamount)) }}</td>
                <td> {{ en2bn(number_format($item->paid_amount)) }}</td>
                <td> {{ en2bn(number_format($item->supplier_total_payable)) }}</td>
                <td> {{ optional($item->entryuser)->name }}</td>
                <td>
                    <span
                        class="btn btn-{{ statusButton($item->payment_status) }} btn-sm">{{ $item->payment_status }}</span>
                </td>
                <td>
                    <span class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                </td>

            </tr>
        @empty
            <tr>
                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">@lang('Total')</th>
            <th> {{ en2bn(number_format($totalqty, 0, '.', '.')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('subtotal'), 2, '.', ',')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('discount'), 2, '.', ',')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('transport_cost'), 2, '.', ',')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('labour_cost'), 2, '.', ',')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('totalamount'), 2, '.', ',')) }}</th>
            <th> {{ en2bn(number_format($itemorders->sum('paid_amount'), 2, '.', ',')) }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table><!-- table end -->

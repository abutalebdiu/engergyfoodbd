<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>@lang('SL')</th>
                <th>@lang('Invoice No')</th>
                <th>@lang('Voucher No')</th>
                <th>@lang('Category')</th>
                <th>@lang('Expense By')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Paid')</th>
                <th>@lang('Due')</th>
                <th>@lang('Date')</th>
                <th>@lang('Note')</th>
                <th>@lang('Entry By')</th>
                <th>@lang('Status')</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $item)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td> <a href="{{ route('admin.expense.show', $item->id) }}"> {{ $item->invoice_no }} </a> </td>
                    <td> {{ $item->voucher_no }} </td>
                    <td class="text-start"> {{ optional($item->category)->name }} </td>
                    <td class="text-start"> {{ optional($item->expenseby)->name }}</td>
                    <td> {{ en2bn(number_format($item->total_amount,2,'.',',')) }}</td>
                    <td>{{ en2bn(number_format($item->expensepayment->sum('amount'),2,'.',','))}}</td>
                    <td>{{ en2bn(number_format($item->total_amount - $item->expensepayment->sum('amount'),2,'.',','))}}</td>
                    <td> {{ en2bn(Date('d-m-Y',strtotime($item->expense_date))) }}</td>
                    <td class="text-wrap">{{ $item->note }}</td>
                    <td> {{ optional($item->entryuser)->name }}</td>
                    <td>
                        <span
                            class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                    </td>
                     <td>
                        <div class="btn-group">
                            <button data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('admin.expense.show', $item->id) }}">
                                        <i class="bi bi-eye"></i> @lang('Show')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.expense.edit', $item->id) }}">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>
                                </li>
                                <li>
                                    <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                        data-question="@lang('Are you sure to remove this data from this list?')"
                                        data-action="{{ route('admin.expense.destroy', $item->id) }}">
                                        <i class="fa fa-trash-alt"></i> @lang('Remove')
                                    </button>
                                </li>
                            </ul>
                        </div>
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
                <th colspan="4"></th>
                <th>@lang('Total')</th>
                <th>{{ en2bn(number_format($expenses->sum('total_amount'),2,'.',',')) }}</th>
                <th colspan="7"></th>
            </tr>
        </tfoot>
    </table><!-- table end -->
</div>
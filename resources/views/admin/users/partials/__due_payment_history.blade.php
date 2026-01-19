<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th>@lang('SL')</th>
            <th>@lang('Date')</th>
            <th>@lang('Customer')</th>
            <th>@lang('Payment Method')</th>
            <th>@lang('Account')</th>
            <th>@lang('Amount')</th>
            <th>@lang('Note')</th>
            <th>@lang('Entry User')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suppliersduepayments as $item)
            <tr>
                <td>
                    <div class="btn-group">
                        <button data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a
                                    href="{{ route('admin.customerduepayment.edit', $item->id) }}">
                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $item->date }}</td>
                <td> {{ optional($item->supplier)->name }} </td>
                <td> {{ optional($item->paymentmethod)->name }} </td>
                <td> {{ optional($item->account)->title }} </td>
                <td> {{ number_format($item->amount, 2, '.', ',') }}</td>
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
            <th colspan="6">@lang('Total')</th>
            <th>{{ number_format($suppliersduepayments->sum('amount'), 2, '.', ',') }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table><!-- table end -->
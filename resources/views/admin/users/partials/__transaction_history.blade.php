<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('SL')</th>
            <th>@lang('T. No')</th>
            <th>@lang('Module')</th>
            <th>@lang('Invoice No')</th>
            <th>@lang('Method')</th>
            <th>@lang('Account')</th>
            <th>@lang('Credit')</th>
            <th>@lang('Debit')</th>
            <th>@lang('Balance')</th>
            <th>@lang('Type')</th>
            <th>@lang('Note')</th>
            <th>@lang('Client/Party')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactionhistories as $item)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $item->txt_no }} </td>
                <td> {{ optional($item->moduletype)->name }}</td>
                <td> {{ $item->invoice_no }}</td>
                <td> {{ optional($item->paymentmethod)->name }}</td>
                <td> {{ optional($item->account)->title }}</td>
                <td>
                    @if ($item->cdf_type == 'credit')
                        {{ number_format($item->amount) }}
                    @endif
                </td>
                <td>
                    @if ($item->cdf_type == 'debit')
                        {{ number_format($item->amount) }}
                    @endif
                </td>
                <td>{{ $item->per_balance }}</td>
                <td> {{ $item->cdf_type }}</td>


                <td> {{ $item->note }}</td>
                <td> {{ optional($item->client)->name }}
                    ({{ optional($item->client)->company_name }})
                </td>
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
            <th colspan="6">Total</th>
            <td>{{ number_format($transactionhistories->where('cdf_type', 'credit')->sum('amount')) }}
            </td>
            <td>{{ number_format($transactionhistories->where('cdf_type', 'debit')->sum('amount')) }}
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table><!-- table end -->
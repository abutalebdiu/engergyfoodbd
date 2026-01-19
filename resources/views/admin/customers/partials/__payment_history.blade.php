<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('SL')</th>
            <th>@lang('Tnx No')</th>
            <th>@lang('Customer Name')</th>
            <th>@lang('Order No')</th>
            <th>@lang('Payment Method')</th>
            <th>@lang('Account')</th>
            <th>@lang('Amount')</th>
            <th>@lang('Date')</th>
            <th>@lang('Entry By')</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orderpayments as $oitem)
            <tr>
                <td> {{ $loop->iteration }} </td>
                <td> {{ $oitem->tnx_no }} </td>
                <td> {{ optional($oitem->customer)->name }} </td>
                <td> {{ optional($oitem->order)->oid }} </td>
                <td> {{ optional($oitem->paymentmethod)->name }}</td>
                <td> {{ optional($oitem->account)->title }}</td>
                <td> {{ number_format($oitem->amount) }} </td>
                <td> {{ $oitem->date ?? $oitem->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ optional($oitem->entryuser)->name }}</td>

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
            <th>Total</th>
            <th>{{ number_format($orderpayments->sum('amount')) }}</th>

            <th></th>
        </tr>
    </tfoot>
</table>
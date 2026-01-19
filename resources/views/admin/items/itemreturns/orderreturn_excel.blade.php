<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Return List</title>
</head>

<body>
    <p>Order Return List</p>
    <table border="1" >
        <thead>
            <tr>
                <th>@lang('SL')</th>
                <th>@lang('Order ID') </th>
                <th>@lang('Customer')</th>
                <th>@lang('QTY')</th>
                <th>@lang('Amount')</th>
                <th>@lang('Date')</th>
                <th>@lang('Entry By')</th>
                <th>@lang('Payment Status')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orderreturns as $item)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td> {{ optional($item->order)->oid }}</td>
                    <td> {{ optional($item->customer)->name }}</td>
                    <td> {{ optional($item->orderreturndetail)->sum('qty') }}</td>
                    <td> {{ number_format($item->totalamount) }}</td>
                    <td> {{ $item->created_at->format('d-m-Y') }} </td>
                    <td> {{ optional($item->entryuser)->name }}</td>
                    <td><span
                            class="btn btn-{{ statusButton($item->payment_status) }} btn-sm">{{ $item->payment_status }}</span>
                    </td>
                   
                </tr>
            @empty
                <tr>
                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table><!-- table end --> 
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order List</title>
</head>

<body>
    <p>Order List</p>
    
        <table border="1">
            <thead>
                <tr>
                    <th>@lang('SL')</th>
                    <th>@lang('Tnx No')</th>
                    <th>@lang('Customer Name')</th>
                    <th>@lang('Order No')</th>                            
                    <th>@lang('Amount')</th>
                    <th>@lang('Payment Method')</th>
                    <th>@lang('Account')</th>                          
                    <th>@lang('Date')</th>
                    <th>@lang('Entry By')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orderpayments as $item)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $item->tnx_no }} </td>
                        <td> {{ optional($item->customer)->name }} </td>
                        <td> {{ optional($item->order)->oid }} </td>                                
                        <td> {{ number_format($item->amount) }} </td>
                        <td> {{ optional($item->paymentmethod)->name }}</td>
                        <td> {{ optional($item->account)->title }}</td>
                    
                        <td> {{ $item->date }}</td>
                        <td>{{ optional($item->entryuser)->name }}</td>
                        <td>
                            <span
                                class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
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
                    <th colspan="3"></th>
                    <th>Total</th>
                    <th>{{ number_format($orderpayments->sum('amount'),2) }}</th>
                </tr>
            </tfoot>
        </table><!-- table end -->
    
</body>

</html>

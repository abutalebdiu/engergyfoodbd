<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Return Payment List</title>
</head>

<body>
    <p>Order Return Payment List</p>
    
    <table border="1" >
        <thead>
            <tr>
                <th>@lang('SL')</th>                           
                <th>@lang('Customer Name')</th>
                <th>@lang('Sales Order No')</th>                           
                <th>@lang('Payment Method')</th>
                <th>@lang('Account')</th>   
                <th>@lang('Amount')</th>                          
                <th>@lang('Date')</th>
                <th>@lang('Entry By')</th>                          
            </tr>
        </thead>
        <tbody>
            @forelse($orderreturnpayments as $item)
                <tr>
                    <td> {{ $loop->iteration }} </td>                                
                    <td> {{ optional($item->customer)->name }} </td>
                    <td> {{ optional($item->orderreturn->order)->oid }} </td>                               
                    <td> {{ optional($item->paymentmethod)->name }}</td>
                    <td> {{ optional($item->account)->title }}</td>      
                    <td> {{ number_format($item->amount,2) }}</td>                          
                    <td> {{ $item->date }}</td>
                    <td>{{ optional($item->entryuser)->name }}</td>                                
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
                <th>Total</th>
                <th>{{ number_format($orderreturnpayments->sum('amount'),2) }}</th>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table><!-- table end -->
    
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Item Order Payment List')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 12px;
        }

        table td {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">
            <div class="print-header" style="text-align: center;margin-bottom:15px">
                <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                <p style="margin: 0;padding:0">{{ $general->address }}</p>
                <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
            </div>
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Item Order List')</h5>
            <p style="margin: 0;padding:0;text-align:right">@lang('Date') : {{ en2bn(Date('d-m-Y')) }}</p>
            <div class="product-detail">
                <table border="1">
                    <thead>
                        <tr>
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
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalqty = 0;
                        @endphp
                        @forelse($items as $item)
                        @php
                            $totalqty+=$item->itemOrderDetail->sum('qty');
                        @endphp
                            <tr>

                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ $item->iid }} </td>
                                <td> {{ $item->reference_invoice_no }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td  style="text-align: left"> {{ optional($item->supplier)->name }}</td>
                                <td> {{ en2bn($item->itemOrderDetail->sum('qty')) }}</td>
                                <td> {{ en2bn(number_format($item->subtotal)) }}</td>
                                <td> {{ en2bn(number_format($item->discount)) }}</td>
                                <td> {{ en2bn(number_format($item->transport_cost)) }}</td>
                                <td> {{ en2bn(number_format($item->labour_cost)) }}</td>
                                <td> {{ en2bn(number_format($item->totalamount)) }}</td>
                                <td> {{ en2bn(number_format($item->paid_amount)) }}</td>
                                <td> {{ en2bn(number_format($item->supplier_total_payable)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tr>
                        <th colspan="5">@lang('Total')</th>
                        <th> {{ en2bn(number_format( $totalqty,0,'.','.')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('subtotal'),2,'.',',')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('discount'),2,'.',',')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('transport_cost'),2,'.',',')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('labour_cost'),2,'.',',')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('totalamount'),2,'.',',')) }}</th>
                        <th> {{ en2bn(number_format($items->sum('paid_amount'),2,'.',',')) }}</th>
                        <th></th>
                    </tr>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Daily Report')</title>
    <style>
        table {
            border-collapse: collapse;
            widtd: 100%;
            text-align: center;
        }

        table th {
            font-size: 13px;
        }

        table td {
            font-size: 12px;
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
            <h5 style="text-align: center;margin: 0;padding:0">@lang('Daily Sales & Collection Report')</h5>
            <p style="margin: 0;padding:0;"> <span style="margin: 0;padding:0;text-align:left;float:left">@lang('Date') : {{ en2bn(Date('d-m-Y',strtotime($date))) }}</span></p>
            <div class="product-detail">
                 <table border="1">
                    <tdead>
                        <tr>
                            <td>@lang('ID')</td>
                            <td style="width:150px">@lang('Name of Distributor')</td>
                            <td style="width:150px">@lang('Address')</td>
                            <td style="width:130px">@lang('Marketer')</td>
                            <td>@lang('Commission')</td>
                            <td>@lang('Commission Type')</td>
                            <td>@lang('Previous Dues')</td>
                            <td>@lang('Challan Amount')</td>
                            <td>@lang('Good Returns')</td>
                            <td>@lang('Damage Returns')</td>
                            <td>@lang('Net Challan')</td>
                            <td>@lang('Total Commission')</td>
                            <td>@lang('Order Dues')</td>
                            <td>@lang('Today Collection')</td>
                            <td>@lang('Today Dues')</td>
                            <td>@lang('Due Collection')</td>
                            <td>@lang('Net Dues')</td>
                            <td>@lang('Net Sales This Month')</td>
                        </tr>
                    </tdead>
                    <tbody>
                        @php
                            $totalprevious_due  = 0;
                            $totalsub_total     = 0;
                            $totalreturn_amount = 0;
                            $totalproductDamage = 0;
                            $totalnet_amount    = 0;
                            $totalcommission    = 0;
                            $totalgrand_total   = 0;
                            $totalpaid_amount   = 0;
                            $totalorder_due     = 0;
                            $totalduePayments   = 0;
                            $overalldue         = 0;
                            $totalmonthly_sales = 0;
                        @endphp
                        
                        
                       @foreach($customers as $customer)
                            <tr>
                                <td style="text-align:left">{{ en2bn($customer->uid) }}</td>
                                <td style="text-align:left">{{ $customer->name }}</td>
                                <td style="text-align:left">{{ $customer->address }}</td>
                                <td style="text-align:left">{{ $customer->reference?->name }}</td>
                                <td style="text-align:left">{{ en2bn($customer->commission) }}</td>
                                <td style="text-align:left">{{ __($customer->commission_type) }}</td>
                                @php
                                    $order = $customer->orders->first(); // Get tde order for tde specific date
                                @endphp
                                @if($order)
                                
                                @php
                                    $totalprevious_due  += $order->previous_due;
                                    $totalsub_total     += $order->sub_total;
                                    $totalreturn_amount += $order->return_amount;
                                    $totalnet_amount    += $order->net_amount;
                                    $totalcommission    += $order->commission;
                                    $totalgrand_total   += $order->grand_total;
                                    $totalpaid_amount   += $order->paid_amount;
                                    $totalorder_due     += $order->order_due;
                                    $overalldue         += $order->customer_due-$customer->duePayments->sum('amount');
                                @endphp
                        
                        
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->previous_due,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->sub_total,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->return_amount,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->productDamage->sum('total_amount'),2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->net_amount,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->commission,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->grand_total,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->paid_amount,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->order_due,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->duePayments->sum('amount'),2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->customer_due-$customer->duePayments->sum('amount'),2)) }}</td>
                                @else
                                    @php
                                        $totalprevious_due  += $customer->receivable($customer->id);
                                    @endphp
                                                
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(($customer->receivable($customer->id)+$customer->duePayments->sum('amount')),2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->productDamage->sum('total_amount'),2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->duePayments->sum('amount'),2)) }}</td>
                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->receivable($customer->id),2)) }}</td>
                                @endif
                                
                                @php
                                    $totalproductDamage += $customer->productDamage->sum('total_amount');
                                    $totalduePayments   += $customer->duePayments->sum('amount');
                                    $totalmonthly_sales += $customer->monthly_sales;
                                @endphp
                                
                                
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->monthly_sales,2)) }}</td>
                                
                            </tr>
                            @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">@lang('Total')</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalprevious_due,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalsub_total,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalreturn_amount,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalproductDamage,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalnet_amount,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalcommission,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalgrand_total,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalpaid_amount,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalorder_due,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalduePayments,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($overalldue,2)) }}</td>
                            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalmonthly_sales,2)) }}</td>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
</body>

</html>

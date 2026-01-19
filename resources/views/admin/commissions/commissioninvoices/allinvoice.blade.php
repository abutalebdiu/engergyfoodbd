<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Commission Invoice')</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 20pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .customer-info {}
        
        .col-md-3{
            width:20%;
            float:left;
        }
        
        .col-md-12{
            width:100%;
            float:left;
        }
        
        h6{
            padding:0;
            margin:0;
        }
        .card{
            padding:5px;
            text-align:left;
           
        }
        .card-body{
           padding:5px;
           box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
        }
        .text-success{
            color:green;
        }
        
    </style>
</head>

<body>
    <div>

        
        @foreach ($commissioninvoices as $commissioninvoice)
            <div class="wrapper" style="height: 16.5in">
                <div class="print-header"
                    style="text-align: center;margin-bottom:1px;padding-bottom:5px;border-bottom:1px solid #000;padding-top:30px">
                    <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                    <p style="margin: 0;padding:0">{{ $general->address }}</p>
                    <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
                </div>

                <div class="customer-info">
                    <p>
                        <b>@lang('ID')</b> : {{ en2bn($commissioninvoice->customer?->uid) }}, <br>
                        <b>@lang('Name')</b> : {{ $commissioninvoice->customer?->name }}, <br>
                        <b>@lang('Mobile')</b> : {{ en2bn($commissioninvoice->customer?->mobile) }}, <br>
                        <b>@lang('Address')</b> : {{ $commissioninvoice->customer?->address }} <br>
                        <b>@lang('Commission')</b> : {{ __($commissioninvoice->customer?->commission_type) }}
                    </p>
                </div>
                <h4 style="text-align:center;padding:0;margin:0">@lang('Commission Invoice')</h4>
                <div class="product-detail">
                    @if ($commissioninvoice->orders)
                        <div class="table-responsive">
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>@lang('OID')</th>
                                        <th>@lang('Date')</th>
                                        <th>@lang('qty')</th>
                                        <th>@lang('Sub Total')</th>
                                        <th>@lang('Return Amount')</th>
                                        <th>@lang('Net Amount')</th>
                                        <th>@lang('Commission')</th>
                                        <th>@lang('Grand Total')</th>
                                        <th>@lang('Paid Amount')</th>
                                        <th>@lang('Due Amount')</th>
                                        <th>@lang('Commission Status')</th>
                                        <th>@lang('Previous Due')</th>
                                        <th>@lang('Total Due Amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalqty = 0;
                                        $orders = $commissioninvoice->orders;
                                    @endphp
                                    @forelse($orders as $item)
                                        @php $totalqty += $item->orderdetail->sum('qty'); @endphp
                                        <tr>
                                            <td> {{ $item->oid }} </td>
                                            <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>

                                            <td> {{ en2bn($item->orderdetail->sum('qty')) }}</td>
                                            <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->return_amount, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->paid_amount, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->order_due, 2, '.', ',')) }}</td>
                                            <td> {{ $item->commission_status }} </td>
                                            <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                                            <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
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

                                        <th colspan="2">@lang('Total')</th>
                                        <th>{{ en2bn($totalqty) }}</th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('sub_total'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('return_amount'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}
                                        </th>


                                        <th>
                                            {{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('previous_due'), 2, '.', ',')) }}
                                        </th>
                                        <th>
                                            {{ en2bn(number_format($orders->sum('customer_due'), 2, '.', ',')) }}
                                        </th>

                                    </tr>
                                </tfoot>
                            </table><!-- table end -->
                        </div>
                    @endif
                </div>
                
                
                @php
                    
                    $year       = Date('Y');
                    $month      = $commissioninvoice->month_id;
                    $startDate  = Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
                    $endDate    = Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
                    $customerduepayments = App\Models\Account\CustomerDuePayment::whereBetween('date',[$startDate,$endDate])->where('customer_id',$commissioninvoice->customer_id)->get();
                    
                    $last_month_due = App\Models\Commission\CommissionInvoice::where('month_id',$commissioninvoice->month_id-1)->where('customer_id',$commissioninvoice->customer_id)->get();
                    
                @endphp
                
                <div class="row">
                    @if($customerduepayments->count()>0)
                        <br>
                        <p style="padding:0;margin:0">Date Wise Due Payment</p>
                        @foreach($customerduepayments as $customerduepayment)
                         <div class="col-12 col-md-3">
                            <div class="card card mb-3 shadow">
                                <div class="card-body">
                                    <h6 class="mb-0 text-capitalize">
                                        {{ en2bn(Date('d-m-Y', strtotime($customerduepayment->date))) }} -
                                        {{ en2bn(number_format($customerduepayment->amount, 2, '.', ',')) }} 
                                         
                                    </h6>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="col-12 col-md-12">
                            <div class="card card mb-3 shadow">
                                <div class="card-body">
                                    <h6 class="mb-0 text-capitalize">
                                       Total -  {{ en2bn(number_format($customerduepayments->sum('amount'), 2, '.', ',')) }} 
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="summery" style="width: 45%;padding-top:10px">
                    <table border="1">
                        <tbody>
                            <tr>
                                <th style="text-align:left">@lang('Total Order Amount')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($orders->sum('net_amount'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Current Month Commission')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($orders->sum('commission'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('After Commission Total')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($orders->sum('grand_total'), 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Total Order Paid Amount')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($orders->sum('paid_amount'), 2, '.', ',')) }}</td>
                            </tr>
                           
                            <tr>
                                <th style="text-align:left">@lang('Current Month Due')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($orders->sum('order_due'), 2, '.', ',')) }}</td>
                            </tr>
                           
                            <tr>
                                <th style="text-align:left">@lang('Last Month Due')</th>
                               <td style="text-align:right;padding-right:10px"> {{  en2bn(number_format($commissioninvoice->last_month_due, 2, '.', ',')) }}  </td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Customer Due Payment')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($commissioninvoice->customer_due_payment, 2, '.', ',')) }}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left">@lang('Total Company/Customer Receivable')</th>
                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($commissioninvoice->amount, 2, '.', ',')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="signature-detail">
                    <table style="width: 100%;text-align:center;margin-top:15%">
                        <tr>
                            <td><span style="border-top:1px solid #000;">@lang('Prepared By')</span></td>
                            <td><span style="border-top:1px solid #000;">@lang('Authority Signature')</span></td>
                            <td><span style="border-top:1px solid #000;">@lang('Receiver')</span></td>
                        </tr>
                    </table>
                </div>
                <div class="page-footer-notice" style="margin-top:10%;font-size:14px">
                    <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>

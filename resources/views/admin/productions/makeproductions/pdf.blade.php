<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Make Productions')  ({{ $date }})</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 14px;
        }

        table td {
            font-size: 13px;
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
            <h4 style="text-align: center;margin: 0;padding:0">@lang('Make Productions')</h4>
            <pstyle="text-align: center;margin: 0;padding:0">@lang('Department'): {{ $department->name }}, Production Date: {{ $date }}</p> 
            <div class="product-detail">
                @php
                    $i = 1;
                @endphp
                @foreach ($makeproductions->chunk(40) as $makeproduction)
                     
                    <table border="1">
                            <thead>
                                <tr class="border-bottom">
                                    <th>@lang('SL No')</th>
                                    <th>@lang('Product')</th>
                                    <th>@lang('QTY')</th>
                                    <th>@lang('Unit')</th>
                                    <th>@lang('Weight') (গ্রাম)</th>
                                    <th>@lang('Weight')  (কেজি)</th>
                                    <th>@lang('Unit Price') </th>
                                    <th>@lang('Total Price') </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalweightgram = 0;
                                    $totalweightkg   = 0;
                                    $totamamount     = 0;
                                @endphp
                                @foreach ($makeproduction as $key => $production)
                                    <tr>
                                        <td>{{ en2bn($i++) }} </td>
                                        <td style="text-align: left">  {{ optional($production->item)->name }} </td>
                                        <td>{{ en2bn($production->qty) }}</td>
                                        <td>{{ en2bn($production->item->unit->name) }}</td>
                                        <td>{{ en2bn(optional($production->item)->weight_gram * $production->qty) }}</td>
                                        <td>{{ en2bn((optional($production->item)->weight_gram * $production->qty)/1000) }}</td>
                                        
                                         @php
                                            $totalweightgram += (optional($production->item)->weight_gram * $production->qty);
                                            $totalweightkg   += ((optional($production->item)->weight_gram * $production->qty)/1000);
                                            $totamamount     += optional($production->item)->price  * $production->qty;
                                        @endphp
                                        <td>{{ en2bn(optional($production->item)->price) }}</td>
                                        <td>{{ en2bn(optional($production->item)->price  * $production->qty)   }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">@lang('Total')</th>
                                    <th>{{ en2bn($totalweightgram) }}</th>
                                    <th>{{ en2bn($totalweightkg) }}</th>
                                    <th></th>
                                    <th>{{ en2bn($totamamount) }}</th>
                                    
                                </tr>
                            </tfoot>
                        </table>
                     
                @endforeach
            </div>
            
        </div>
    </div>
</body>

</html>

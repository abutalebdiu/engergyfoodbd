<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Date Wise Customers orders List')</title>
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
            margin: 20pt 10pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="print-header" style="text-align: center;margin-bottom:15px">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>

        <div class="quotation-title">
            <p style="margin: 0;padding:0;width:100%;text-align: center">
                <span style="border-bottom:1px solid #000">@lang('Date Wise Customers Order Count List')</span>
            </p>
            <p style="margin: 0;padding:0;width:100%;text-align:left">
                @lang('Date') : {{ en2bn(Date('d-m-Y', strtotime($start_date))) }} হতে
                {{ en2bn(Date('d-m-Y', strtotime($end_date))) }} পর্যন্ত
            </p>

        </div>

        <div class="product-detail">
            <table border="1">
                <thead>
                    <tr>
                        <th>@lang('SL No')</th>
                        <th>@lang('UID')</th>
                        <th>@lang('Customer Name')</th>
                        @foreach ($dates as $date)
                            <th>{{ en2bn(date('d', strtotime($date))) }}</th>
                        @endforeach
                        <th>@lang('Total Order')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotals = array_fill_keys($dates, 0);
                        $allTotal = 0;
                    @endphp

                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ en2bn($loop->iteration) }}</td>
                            <td>{{ en2bn($customer->uid) }}</td>
                            <td style="text-align: left">{{ $customer->name }}</td>
                            @php $rowTotal = 0; @endphp
                            @foreach ($dates as $date)
                                @php
                                    $count = $orderData[$customer->id][$date] ?? 0;
                                    $rowTotal += $count;
                                    $grandTotals[$date] += $count;
                                    $allTotal += $count;
                                @endphp
                                <td>{{ en2bn($count) }}</td>
                            @endforeach
                            <td>{{ en2bn($rowTotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">@lang('Total')</th>
                        @foreach ($dates as $date)
                            <th>{{ en2bn($grandTotals[$date]) }}</th>
                        @endforeach
                        <th>{{ en2bn($allTotal) }}</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</body>

</html>

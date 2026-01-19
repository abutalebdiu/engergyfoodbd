<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Products List')</title>
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
            font-size: 14pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 0pt 30pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:1px">
            <h3 style="margin: 0;padding:0;font-size:17pt">{{ $general->site_name }}</h3>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
        </div>

        <table border="1">
            <thead>
                <tr>
                    <th style="width: 3%">@lang('SL No')</th>
                    <th style="width: 20%">@lang('Name')</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="width: 10%">@lang('Total')</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($productswithgroupes as $departmentId => $products)
                    @php
                        $departmentName = optional($products->first()->department)->name;
                    @endphp
                    <tr>
                        <td colspan="11" class="font-weight-bold text-primary text-start">
                            {{ $departmentName ?: 'No Department' }}
                        </td>
                    </tr>
                    @foreach ($products->where('status', 'Active') as $key => $item)
                        <tr @if ($loop->iteration % 2 != 0) style="background-color:#ddd" @endif>
                            <td> {{ en2bn($loop->iteration) }} </td>
                            <td style="text-align: left"> {{ $item->name }} </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

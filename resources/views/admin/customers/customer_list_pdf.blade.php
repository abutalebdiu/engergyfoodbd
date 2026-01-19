<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Customers List')</title>
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

        section::after {
            content: "";
            display: table;
            clear: both;
        }

        .products {
            float: left;
            width: 49%;
            margin-left: 2px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">
            <div>
                <div class="print-header" style="text-align: center;margin-bottom:15px">
                    <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                    <p style="margin: 0;padding:0">{{ $general->address }}</p>
                    <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}
                    </p>
                </div>
                <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Customers List')</h5>

                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL No')</th>
                            <th>@lang('ID')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Address')</th>
                            <th>@lang('Commission Type')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Marketer Name')</th>
                            <th>@lang('Opening Due')</th>
                            <th>@lang('Total Due')</th>
                            <th>@lang('Total Order')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totaldue = 0;
                            $totalorder = 0;
                        @endphp
                        @forelse($users as $user)
                            @php
                                $totaldue += $user->receivable($user->id);
                                $totalorder += $user->orders->count();
                            @endphp
                            <tr>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td>{{ en2bn($user->uid) }}</td>
                                <td style="text-align: left;padding-left:5px">{{ $user->name }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td style="text-align: left;padding-left:5px">{{ $user->address }}</td>
                                <td>{{ __($user->commission_type) }}</td>
                                <td>{{ en2bn($user->commission) }}</td>
                                <td>{{ optional($user->reference)->name }} </td>
                                <td style="text-align: right;padding-right:5px">
                                    {{ en2bn(number_format($user->opening_due, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:5px">
                                    {{ en2bn(number_format($user->receivable($user->id), 2)) }}</td>
                                <td>{{ en2bn($user->orders->count()) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">@lang('No data found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tr>
                        <th colspan="9">@lang('Total')</th>
                        <th>{{ en2bn(number_format($totaldue, 2, '.', '')) }}</th>
                        <th>{{ en2bn(number_format($totalorder)) }}</th>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</body>

</html>

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

        
    </style>
</head>

<body>
    
        <div class="wrapper"> 
            <div>
                <div class="print-header" style="text-align: center;margin-bottom:15px">
                    <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                    <p style="margin: 0;padding:0">{{ $general->address }}</p>
                    <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}
                    </p>
                </div>

                <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Suppliers List')</h5>
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Company Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Address')</th>
                            <th>@lang('Opending Payable')</th>
                            <th>@lang('Total Payable')</th>                           
                        </tr>
                    </thead>
                    <tbody id="UserTable">
                        @php $totalpayable = 0;  @endphp
                        @forelse($users as $user)
                        @php $totalpayable += $user->payable($user->id);  @endphp
                            <tr>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td style="text-align: left">{{ $user->name }}</td>
                                <td style="text-align: left">{{ $user->company_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td style="text-align: left">{{ $user->mobile }}</td>
                                <td style="text-align: left">{{ $user->address }}</td>
                                <td>{{ en2bn(number_format($user->opening_due, 2, '.', ',')) }}</td>
                                <td>{{ en2bn(number_format($user->payable($user->id), 2, '.', ',')) }}</td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">@lang('Total') </td>
                            <td>{{ en2bn(number_format($totalpayable, 2, '.', ',')) }}</td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
                                    
            </div>         
        </div>
     
</body>
</html>

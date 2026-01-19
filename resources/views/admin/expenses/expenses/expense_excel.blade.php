<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @lang('Expense List') </title>
</head>

<body>
    <div>
        <div class="wrapper">
            <div>
                <table style="text-align: center">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                    <tr>
                        <th colspan="9"></th>
                    </tr>
                    <tr>
                        <th colspan="9" style="text-align: center">{{ $general->site_name }}</th>
                    </tr>
                    <tr>
                        <th colspan="9" style="text-align: center">{{ $general->address }}</th>
                    </tr>
                    <tr>
                        <th colspan="9" style="text-align: center">অফিস: {{ $general->phone }}, হেল্প
                            লাইন:{{ $general->mobile }}</th>
                    </tr>
                    <tr>
                        <th colspan="9"></th>
                    </tr>
                    <tr>
                        <th colspan="9" style="text-align: center">
                            @lang('Expense List')
                        </th>
                    </tr>
                    <tr>
                        <th colspan="9" style="text-align: right">
                            @lang('Date'): {{ Date('d-m-Y') }}
                        </th>
                    </tr>
                </table>
            </div>


            <div>
                <table border="1">
                    <thead>
                        <tr>
                            <th style="width: 5%">@lang('SL No')</th>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Voucher No')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Expense By')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->invoice_no }} </td>
                                <td> {{ $item->voucher_no }} </td>
                                <td> {{ optional($item->category)->name }} </td>
                                <td> {{ optional($item->expenseby)->name }}</td>
                                <td> {{ $item->total_amount }}</td>
                                <td> {{ $item->expense_date }}</td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                                <td>
                                    {{ $item->status }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th>@lang('Total')</th>
                            <th>{{ $expenses->sum('total_amount') }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                @lang('IN WORD')
                            </th>
                            <th colspan="6">
                                {{ numberToBanglaWord($expenses->sum('total_amount')) }} @lang('Taka Only')
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
</body>

</html>

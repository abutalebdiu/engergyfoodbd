<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @lang('Expense List') </title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 13px;
        }
        table td{
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
            <h4 style="text-align: center">@lang('Expense List')</h4>
            <p style="text-align: right;padding:0;margin:0">Date: {{ Date('d-m-Y') }}</p>
            <div class="product-detail">
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
                            <th>@lang('Note')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>                           
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->invoice_no }} </td>
                                <td> {{ $item->voucher_no }} </td>
                                <td> {{ optional($item->category)->name }} </td>
                                <td> {{ optional($item->expenseby)->name }}</td>
                                <td> {{ $item->total_amount }}</td>
                                <td> {{ $item->expense_date }}</td>
                                <td> {{ $item->note }}</td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                 
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
                            <th>@lang('Total')</th>
                            <th>{{ $expenses->sum('total_amount') }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                             <th  colspan="2">
                                @lang('IN WORD')
                             </th>
                             <th colspan="6">
                                {{  numberToBanglaWord($expenses->sum('total_amount')) }} @lang('Taka Only')
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

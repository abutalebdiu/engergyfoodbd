@if ($layout)
    @extends('report.print.layouts.app')

    @section('title')
    Profit & Loss Reports
    @endsection
    @section('content')
@endif
@if ($layout)
<div class="mt-10">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @endif
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Opening Balance')</td>
                            <td class="text-end">{{ number_format($data['opening_balance'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase:') <p>(Exc. tax, Discount)</p></td>
                            <td class="text-end">{{ number_format($data['total_purchase'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase Vat:') </td>
                            <td class="text-end">{{ number_format($data['total_purchase_vat'], 3) }}</td>
                        </tr>
                        <tr>
                            <td>@lang('Total Purchase Discount:') </td>
                            <td class="text-end">{{ number_format($data['total_purchase_discount'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase Return:') </td>
                            <td class="text-end">{{ number_format($data['total_purchase_return'], 3) }}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        @if ($layout)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @endif
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Closing Amount')</td>
                            <td class="text-end">{{ number_format($data['total_clossing'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Sales:') <p>(Exc. tax, Discount)</p>
                            </td>
                            <td class="text-end">{{ number_format($data['total_order_amount'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order discount:') </td>
                            <td class="text-end">{{ number_format($data['total_order_discount'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order Vat:') </td>
                            <td class="text-end">{{ number_format($data['total_order_vat'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order Return:') </td>
                            <td class="text-end">{{ number_format($data['total_order_return'], 3) }}</td>
                        </tr>

                    </table>

                    @if ($layout)
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="gross-profit">
                        <h5>Gross Profit: {{ $gross_profit =  number_format(($data['total_order_amount'] - $data['total_purchase']), 3)  }} $</h5>
                        <p>(Total Sell price - Total Purchase price)</p>
                    </div>

                    <div class="net-profit">
                        <h5>Net Profit: {{ $gross_profit }}$</h5>
                        <p>Gross Profit + (Total sell shipping charge + Sell additional expenses + Total Stock Recovered
                            + Total Purchase discount + Total sell round off )
                            - ( Total Stock Adjustment + Total Expense + Total purchase shipping charge + Total transfer
                            shipping charge + Purchase additional expenses + Total Sell discount + Total customer reward
                            )</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if ($layout)
    </div>
</div>
@endif

@if ($layout)
@endsection
@endif



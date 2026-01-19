@extends('admin.layouts.app', ['title' => 'Profit & Loss Report'])
@section('panel')
@include('report.layouts.default', ['title' => 'Profit & Loss Report', 'url' => 'admin.reports.profitloss', ['range_date' => $range_date ? $range_date : null]])

<section>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Opening Balance')</td>
                            <td class="text-end">{{ number_format($opening_balance, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase:') <p>(Exc. tax, Discount)</p></td>
                            <td class="text-end">{{ number_format($total_purchase, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase Vat:') </td>
                            <td class="text-end">{{ number_format($total_purchase_vat, 3) }}</td>
                        </tr>
                        <tr>
                            <td>@lang('Total Purchase Discount:') </td>
                            <td class="text-end">{{ number_format($total_purchase_discount, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase Return:') </td>
                            <td class="text-end">{{ number_format($total_purchase_return, 3) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Closing Amount')</td>
                            <td class="text-end">{{ number_format($total_clossing, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Sales:') <p>(Exc. tax, Discount)</p>
                            </td>
                            <td class="text-end">{{ number_format($total_order_amount, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order discount:') </td>
                            <td class="text-end">{{ number_format($total_order_discount, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order Vat:') </td>
                            <td class="text-end">{{ number_format($total_order_vat, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Order Return:') </td>
                            <td class="text-end">{{ number_format($total_order_return, 3) }}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="gross-profit">
                        <h5>Gross Profit: {{ $gross_profit =  number_format(($total_order_amount - $total_purchase), 3)  }} $</h5>
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

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="product-tab" data-bs-toggle="tab" data-bs-target="#profit-by-products"
                                type="button" role="tab" aria-controls="product" aria-selected="true">Profit By Products</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="category-tab" data-bs-toggle="tab" data-bs-target="#profit-by-category"
                                type="button" role="tab" aria-controls="category" aria-selected="false">Profit By Category</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="brand-tab" data-bs-toggle="tab" data-bs-target="#profit-by-brands"
                                type="button" role="tab" aria-controls="brand" aria-selected="false">Profit By Brands</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profit-by-products" role="tabpanel" aria-labelledby="product-tab">
                            <div>
                                @include('report.profitloss.product')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profit-by-category" role="tabpanel" aria-labelledby="category-tab">
                            <div>
                                @include('report.profitloss.category')
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profit-by-brands" role="tabpanel" aria-labelledby="brand-tab">
                            <div></div>
                                @include('report.profitloss.brand')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    table tr td p {
        font-size: 10px !important;
    }

    p {
        font-size: 11px !important;
    }
</style>
@endpush

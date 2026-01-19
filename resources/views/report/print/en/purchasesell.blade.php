@extends('report.print.layouts.app')

@section('title')
Purchases & Sell
@endsection
@section('content')
<div class="mt-10">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="py-3">
                        @lang('Purchase ')
                    </h3>
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Total Purchase:')</td>
                            <td class="text-end">{{ number_format($data['total_purchase'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Purchase Including tax:')</td>
                            <td class="text-end">{{ number_format($data['purches_include_tax'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Purchase Discount:') </td>
                            <td class="text-end">{{ number_format($data['purchese_discount'], 3) }}</td>
                        </tr>
']
                        <tr>
                            <td>@lang('Purchase Due:') </td>
                            <td class="text-end">{{ number_format($data['purches_due'], 3) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="py-3">
                        @lang('Sell')
                    </h3>
                    <table class="table table-hover table-striped">
                        <tr>
                            <td>@lang('Total Sale:')</td>
                            <td class="text-end">{{ number_format($data['total_sale'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Sale Including tax:') </p>
                            </td>
                            <td class="text-end">{{ number_format($data['sale_include_sale_tax'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Sell Vat:') </td>
                            <td class="text-end">{{ number_format($data['sale_vat'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Total Sell Discount:') </td>
                            <td class="text-end">{{ number_format($data['sale_discount'], 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Sale Due:') </td>
                            <td class="text-end">{{ number_format($data['sale_due'], 3) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="gross-profit">
                        <h5>Overall ((Sale - Sell Return) - (Purchase - Purchase Return) )</h5>
                    </div>
                   </br>
                   </br>
                    <div class="gross-profit">
                        <h6><span>Sale - Purchase:</span> {{ number_format(($data['total_sale'] - $data['total_purchase']), 3) }}</h6>
                        <h6><span>Due amount:</span> <span class="text-danger">-{{ number_format(($data['sale_due'] - $data['purches_due']), 3) }}</span></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



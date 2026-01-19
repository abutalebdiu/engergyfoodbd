@extends('admin.layouts.app', ['title' => 'Purchase & Sell Reports'])
@section('panel')
@include('report.layouts.default', ['title' => 'Purchase & Sell Reports', 'url' => 'admin.reports.purchasesell', ['range_date' => $range_date ? $range_date : null]])

<section>
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
                            <td class="text-end">{{ number_format($total_purchase, 3) }}</td>
                        </tr>

                         

                        <tr>
                            <td>@lang('Total Purchase Discount:') </td>
                            <td class="text-end">{{ number_format($purchese_discount, 3) }}</td>
                        </tr>

                        <tr>
                            <td>@lang('Purchase Due:') </td>
                            <td class="text-end">{{ number_format($purches_due, 3) }}</td>
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
                            <td class="text-end">{{ number_format($total_sale, 3) }}</td>
                        </tr>

                      

                        <tr>
                            <td>@lang('Sale Due:') </td>
                            <td class="text-end">{{ number_format($sale_due, 3) }}</td>
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
                        <h6><span>Sale - Purchase:</span> {{ number_format(($total_sale - $total_purchase), 3) }}</h6>
                        <h6><span>Due amount:</span> <span class="text-danger">-{{ number_format(($sale_due - $purches_due), 3) }}</span></h6>
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

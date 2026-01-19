@extends('admin.layouts.app', ['title' => __('Daily Archive')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Daily Archive')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($datas['start_date'])) value="{{ $datas['start_date'] }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($datas['end_date'])) value="{{ $datas['end_date'] }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary "><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary "><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>


            <div class="row mt-4">
                <div class="col-12">
                    <p class=" mt-5">@lang('Date'): @if (isset($datas['start_date']))
                            {{ en2bn(Date('d-m-Y', strtotime($datas['start_date']))) }} -
                            {{ en2bn(Date('d-m-Y', strtotime($datas['end_date']))) }}
                        @endif
                    </p>

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Quotation QTY')</th>
                            <th>@lang('Quotation Amount')</th>
                            <th>@lang('Order Qty')</th>
                            <th>@lang('Order Amount')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Order Due')</th>
                            <th>@lang('Customer Due Payment')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Product Return Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas['archives'] as $key => $data)
                            <tr>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td>{{ en2bn($data['date']) }}</td>
                                <td>{{ en2bn(number_format($data['quotation_qty'], 0)) }}</td>
                                <td>{{ en2bn(number_format($data['quotation_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_qty'], 0)) }}</td>
                                <td>{{ en2bn(number_format($data['order_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['paid_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_due'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['customer_due_payment'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['commission_amount'], 2)) }}</td>
                                <td>{{ en2bn(number_format($data['order_return_amount'], 2)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('Total')</th>
                            <th></th>
                            <th>{{ en2bn(number_format($datas['total_quotation_qty'], 0)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_quotation_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_qty'], 0)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_paid_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_due'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_customer_due_payment'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_commission_amount'], 2)) }}</th>
                            <th>{{ en2bn(number_format($datas['total_order_return_amount'], 2)) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>



        </div>

    </div>
@endsection

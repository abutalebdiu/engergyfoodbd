@extends('admin.layouts.app', ['title' => __('Products Sales List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Products Sales List')
                <a href="{{ route('admin.product.index') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-list"></i> @lang('Product List')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="productsTable">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer  Name')</th>
                            <th>@lang('Sale/Dealar Price')</th>
                            <th>@lang('QTY')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderdetails as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ en2bn(Date('d-m-Y', strtotime($detail->order?->date))) }}</td>
                                <td>{{ $detail->order->customer?->name }}</td>
                                <td>{{ en2bn(number_format($detail->price, 2, '.', ',')) }}</td>
                                <td>{{ en2bn(number_format($detail->qty, 0, '.', ',')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr>
                            <th colspan="4">@lang('Total')</th>
                            <th>{{ en2bn(number_format($orderdetails->sum('qty'), 0, '.', ',')) }}</th>
                        </tr>
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

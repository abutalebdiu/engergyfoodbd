@extends('admin.layouts.app', ['title' => __('Customer Product Damage Detail')])
@push('style')
    <style>
        table,
        td,
        th {
            padding: 2px !important;
        }
    </style>
@endpush
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Customer Product Damage Detail')
                <a href="{{ route('admin.customerproductdamage.index') }}" class="btn btn-outline-primary btn-sm float-end">
                    <i class="fa fa-list"></i> @lang('Customers Products Damage List')</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Customer Info</h5>
                    <p>
                        @lang('Name') : {{ optional($customerproductdamage->customer)->name }}, <br>
                        @lang('Mobile') : {{ optional($customerproductdamage->customer)->mobile }}, <br>
                        @lang('Address') : {{ optional($customerproductdamage->customer)->address }}
                    </p>
                    <p>@lang('Total') : {{ $customerproductdamage->total_amount }}</p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Product Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>QTY</th>
                        <th>Amount</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($customerproductdamage->customerproductdamagedetail as $odetail)
                        <tr>
                            <td>{{ en2bn($loop->iteration) }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td>{{ en2bn(number_format($odetail->price, 2, '.', '.')) }}</td>
                            <td>{{ en2bn(number_format($odetail->qty, 2, '.', '.')) }}</td>
                            <td>{{ en2bn(number_format($odetail->amount, 2, '.', '.')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark">
                        <td></td>
                        <td></td>
                        <td class="text-white">
                            @lang('Total')
                        </td>

                        <td class="text-white">{{ en2bn($customerproductdamage->customerproductdamagedetail->sum('qty')) }}
                        </td>
                        <td class="text-white">
                            {{ en2bn(number_format($customerproductdamage->customerproductdamagedetail->sum('amount'), 2, '.', '.')) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

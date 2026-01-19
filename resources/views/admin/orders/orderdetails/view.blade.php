@extends('admin.layouts.app', ['title' => __('Order Detail List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Order Detail List')
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('OID')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Qty')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderdetails as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->order->oid }}</td>
                                <td>{{ $order->order->date }}</td>
                                <td>{{ $order->order->customer->name }}</td>
                                <td>{{ $order->product->name }}</td>
                                <td>{{ $order->qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><!-- table end -->
            </div>

            <div class="my-2">
                {{ $orderdetails->appends(request()->except('page'))->links() }}
            </div>

        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')

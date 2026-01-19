@extends('admin.layouts.app',['title'=>'Service Payment History List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Service Payment History List
                <a href="{{ route('admin.serviceinvoicepayment.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i>Receive Service Payment</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>                            
                            <th>@lang('Status')</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>                             
                                <td> {{ optional($item->customer)->name }} </td>
                                <td> {{ optional($item->serviceinvoice->month)->name }} - {{ optional($item->serviceinvoice->year)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ number_format($item->amount) }}</td>
                                
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                 <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app', ['title' => 'Order Return Payment List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Order Return Payment List</h5>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option @if(isset($customer_id)) {{ $customer_id == $customer->id ? "selected" : ""  }} @endif value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date" @if(isset($start_date)) value="{{ $start_date }}"  @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date" @if(isset($end_date)) value="{{ $end_date }}"  @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                         <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
                         <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i> PDF</button>
                         <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Excel</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Customer Name')</th>
                            <th>@lang('Sales Order No')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderreturnpayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->customer)->name }} </td>
                                <td> {{ optional($item->orderreturn->order)->oid }} </td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ number_format($item->amount,2) }}</td>
                                <td> {{ $item->date }}</td>
                                <td>{{ optional($item->entryuser)->name }}</td>
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
                            <th>Total</th>
                            <th>{{ number_format($orderreturnpayments->sum('amount'),2) }}</th>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

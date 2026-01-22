@extends('admin.layouts.app', ['title' => 'Order Return list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">
                @lang('Order Return List')

                <a href="{{ route('admin.orderreturn.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('New Order Return')</a>
            </h6>
            <div></div>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option
                                    @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                    value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            Search</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            PDF</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            Excel</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Order ID') </th>
                            <th>@lang('Customer')</th>
                            <th>@lang('QTY')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Payment Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderreturns as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->order)->oid }}</td>
                                <td> {{ optional($item->customer)->name }}</td>
                                <td> {{ en2bn(optional($item->orderreturndetail)->sum('qty')) }}</td>
                                <td> {{ en2bn(number_format($item->totalamount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }} </td>
                                <td>{!! entry_info($item) !!}</td>
                                <td><span
                                        class="btn btn-{{ statusButton($item->payment_status) }} btn-sm">{{ $item->payment_status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.orderreturn.show', $item->id) }}">
                                                    <i class="fa fa-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.orderreturn.edit', $item->id) }}">
                                                    <i class="fa fa-edit"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.orderreturn.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

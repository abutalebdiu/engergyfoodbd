@extends('admin.layouts.app', ['title' => 'Official Loan List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Customer Loan List
                <a href="{{ route('admin.customerloan.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Customer Loan</a>
            </h6>
        </div>
        <div class="card-body">

            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
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
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Interest')</th>
                            <th>@lang('Total Amount')</th>
                            <th>@lang('Monthly Installment')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Unpaid Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                           @php
                                $paidamount = 0;
                                $unpaidamount = 0;
                          @endphp
                        
                        @forelse($customerloans as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <span class="btn btn-primary btn-sm"> @lang('Action')</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.customerloan.edit', $item->id) }}">
                                                    <i class="fa fa-edit"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.customerloan.show', $item->id) }}">
                                                    <i class="fa fa-eye"></i> @lang('Show')
                                                 </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.customerloan.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ (Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ $item->title }} </td>
                                <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ (number_format($item->amount)) }}</td>
                                <td> {{ (number_format($item->interest)) }}</td>
                                <td> {{ (number_format($item->total_amount)) }}</td>
                                <td> {{ (number_format($item->monthly_settlement)) }}</td>
                                 <td> {{ $item->customerloanpayments->sum('amount') }}</td>
                                <td> {{ $item->total_amount - $item->customerloanpayments->sum('amount') }}</td>
                                <td> {{ $item->note }}</td>
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
                    <tfoot>
                        <tr>
                            <th colspan="7">@lang('Total')</th>
                            <td> {{ (number_format($customerloans->sum('amount'))) }}</td>
                            <td> {{ (number_format($customerloans->sum('interest'))) }}</td>
                            <td> {{ (number_format($customerloans->sum('total_amount'))) }}</td>
                            <td></td>
                            <td> {{ number_format($paidamount) }}</td>
                            <td> {{ number_format($unpaidamount) }}</td>
                            <td></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

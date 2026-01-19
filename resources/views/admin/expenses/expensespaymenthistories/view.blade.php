@extends('admin.layouts.app', ['title' => 'Transaction History List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Transaction History List
                <a href="{{ route('admin.expense.index') }}" class="btn btn-primary btn-sm float-end"><i
                        class="fa fa-list"></i> Expense
                    List</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
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
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>


            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Expense Voucher')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expensespaymenthistories as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.expensepaymenthistory.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.expensepaymenthistory.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->ex_invoice_no }}</td>
                                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }}</td>
                                <td> {{ optional($item->expense)->voucher_no }}</td>
                                <td> {{ en2bn(number_format($item->amount,2,'.',',')) }}</td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>

                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">@lang('Total')</th>
                            <th> {{ en2bn(number_format($expensespaymenthistories->sum('amount'),2,'.',',')) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
                
                {{ $expensespaymenthistories->links() }}
                
            </div>
        </div>
    </div>
    
    
     <x-destroy-confirmation-modal />
@endsection

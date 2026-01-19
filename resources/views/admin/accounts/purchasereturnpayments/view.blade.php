@extends('admin.layouts.app', ['title' => 'Purchase Return Payment List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Purchase Return Payment List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>                           
                            <th>@lang('Supplier Name')</th>
                            <th>@lang('Purchase Order No')</th>                           
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>   
                            <th>@lang('Amount')</th>                          
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>                            
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchasereturnpayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>                                
                                <td> {{ optional($item->supplier)->name }} </td>
                                <td> {{ optional($item->purchasereturn->purchase)->pid }} </td>                               
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>      
                                <td> {{ number_format($item->amount,2) }}</td>                          
                                <td> {{ $item->date }}</td>
                                <td>{{ optional($item->entryuser)->name }}</td>                                
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.ordersupplierpayment.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
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
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th>Total</th>
                            <th>{{ number_format($purchasereturnpayments->sum('amount'),2) }}</th>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

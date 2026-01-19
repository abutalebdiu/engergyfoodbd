@extends('admin.layouts.app', ['title' => 'Supplier Payment list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Supplier Payment List
                <a href="{{ route('admin.ordersupplierpayment.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add New Supplier Payment</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('No')</th>
                            <th>@lang('Supplier Name')</th>
                            <th>@lang('Order No')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Mother Account')</th>

                            <th>@lang('Note')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordersupplierpayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->tnx_no }} </td>
                                <td> {{ optional($item->supplier)->name }} </td>
                                <td> {{ optional($item->purchase)->pid }} </td>
                                <td> {{ number_format($item->amount,2) }}</td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ $item->note }}</td>
                                <td> {{ $item->date }}</td>
                                <td>{{ optional($item->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
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
                            <th colspan="3"></th>
                            <th>Total</th>
                            <th>{{ number_format($ordersupplierpayments->sum('amount'),2) }}</th>
                            <td colspan="8"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

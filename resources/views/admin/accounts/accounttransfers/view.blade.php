@extends('admin.layouts.app', ['title' => 'Account Balance Transfer list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Account Balance Transfer list
                <a href="{{ route('admin.accounttransfer.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add New Transfer</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Method')</th>                           
                            <th>@lang('Account Name')</th>
                            <th>@lang('Account Number')</th>                           
                            <th>@lang('Amount')</th>
                            <th>@lang('From Account')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry User')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounttransfers as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>                             
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ optional($item->account)->account_number }}</td>                              
                                <td> {{ number_format($item->amount,2) }}</td>                               
                                <td> {{ optional($item->fromaccount)->title }}</td>
                                <td> {{ $item->created_at }}</td>
                                <td> 
                                    {!! entry_info($item) !!}
                                </td>
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.accounttransfer.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>                                             
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

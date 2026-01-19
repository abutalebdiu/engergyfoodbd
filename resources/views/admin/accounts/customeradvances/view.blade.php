@extends('admin.layouts.app', ['title' => 'Customer Advance List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Customer Advance list
                <a href="{{ route('admin.customeradvance.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add New Customer Advance</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Entry User')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customeradvances as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>

                                <td> {{ optional($item->customer)->name }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ number_format($item->amount) }}</td>
                                <td> {{ $item->date }}</td>
                                <td> {{ $item->note }}</td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.customeradvance.edit', $item->id) }}">
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

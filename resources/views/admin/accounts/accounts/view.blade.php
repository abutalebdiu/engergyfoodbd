@extends('admin.layouts.app', ['title' => __('Account list')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Account')
                <a href="{{ route('admin.account.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add New Account')</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Method')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Account Number')</th>
                            <th>@lang('Branch')</th>
                            <th>@lang('Routing')</th>
                            <th>@lang('Opening Balance')</th>
                            <th>@lang('Main Balance')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ $item->title }}</td>
                                <td> {{ $item->account_name }}</td>
                                <td> {{ $item->account_number }}</td>
                                <td> {{ $item->branch }}</td>
                                <td> {{ $item->routing }}</td>
                                <td> {{ number_format($item->opening_balance,2) }}</td>
                                <td> {{ number_format($item->balance($item->id),2) }}</td>
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
                                                <a href="{{ route('admin.account.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.account.show', $item->id) }}">
                                                    <i class="bi bi-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.account.dayclosed') }}">
                                                    <i class="bi bi-eye"></i> @lang('Closing Reports')
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

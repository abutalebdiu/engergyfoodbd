@extends('admin.layouts.app', ['title' => __('Transport Expense List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Expense List')
                <a href="{{ route('admin.transportexpense.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add Transport Expense')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transportexpenses as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td style="text-align: left"> {{ $item->name }} </td>
                                <td> {{ $item->description }} </td>
                                <td>
                                    <span class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.transportexpense.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.transportexpense.destroy', $item->id) }}">
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
                </table>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

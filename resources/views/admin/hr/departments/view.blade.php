@extends('admin.layouts.app', ['title' => __('Department')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Department')
                <a href="{{ route('admin.department.create') }}" class="btn btn-primary float-end"> <i class="fa fa-plus"></i>
                    @lang('Add New')
                </a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Position')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->name }} </td>
                                <td> {{ $item->position }} </td>
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="gap-1 fs-6">
                                        <a href="{{ route('admin.department.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="" data-bs-original-title="Edit info"
                                            aria-label="Edit"><i class="fa fa-edit"></i> @lang('Edit')</a>
                                        <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                            data-question="@lang('Are you sure to remove this data from this list?')"
                                            data-action="{{ route('admin.department.destroy', $item->id) }}">
                                            <i class="fa fa-trash-alt"></i> @lang('Remove')
                                        </button>
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
                </table>
            </div>
        </div>
    </div>
    <x-destroy-confirmation-modal />
@endsection

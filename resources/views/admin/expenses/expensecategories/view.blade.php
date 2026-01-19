@extends('admin.layouts.app', ['title' => 'Expense Category List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Expense Category List
                <a href="{{ route('admin.expensecategory.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add New Category</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expensecategories as $expensecategory)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td class="text-start"> {{ $expensecategory->name }} </td>
                                <td> <span
                                        class="btn btn-{{ statusButton($expensecategory->status) }} btn-sm">{{ $expensecategory->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.expensecategory.edit', $expensecategory->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.expensecategory.destroy', $expensecategory->id) }}">
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

    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.expensecategory.create') }}" class="btn btn-sm btn-outline-primary">Add New Expense
                Category</a>
        </div>
    @endpush

    <x-destroy-confirmation-modal />
@endsection

@extends('admin.layouts.app', ['title' => 'Item Category List'])

@section('panel')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                Item Category List
                <a href="{{ route('admin.items.itemCategory.create') }}"
                   class="btn btn-primary btn-sm float-end">
                    <i class="bi bi-plus-circle"></i> Add New
                </a>
            </h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry by')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($itemcategories as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="text-start">{{ $item->name }}</td>

                                <td>
                                    <span class="btn btn-{{ statusButton($item->status) }} btn-sm">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td>
                                    {!! entry_info($item) !!}
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown">
                                            Action
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.items.itemCategory.create', $item->id) }}"
                                                   class="dropdown-item">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.items.itemCategory.destroy', $item->id) }}" confirm="@lang('Are you sure you want to delete this item?')" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash-fill"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center text-muted">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app', ['title' => 'Unit List'])
@section('panel')
    <!--breadcrumb-->
    <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
        <div>
            <h6 class="m-0">Unit List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.items.unit.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Add New </a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Symbol')</th>
                            <th>@lang('Base Unit')</th>
                            <th>@lang('Value')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->name }} </td>
                                <td> {{ $item->symbol }} </td>
                                <td> {{ $item->base_unit }} </td>
                                <td> {{ $item->value }}</td>

                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.items.unit.create', $item->id) }}"
                                                    class="dropdown-item"><i class="bi bi-pencil-fill"></i>Edit</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

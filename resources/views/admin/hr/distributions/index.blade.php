@extends('admin.layouts.app', ['title' => 'Distributor List'])

@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Distributor List</h6>
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">

            <!-- Search Bar -->
            <form action="" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search...">
                <button type="submit" class="btn btn-primary btn-sm ms-2">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- PDF button -->
            <div>
                <a href="" type="button" class="btn btn-primary btn-sm">
                    <i class="bi bi-download"></i> PDF
                </a>
            </div>

            <!-- Add New button -->
            <div>
                <a href="{{ route('admin.distribution.create') }}" type="button" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Add New
                </a>
            </div>
        </div>
    </div>

    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL No')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Total Order')</th>
                            <th>@lang('Order Amount')</th>
                            <th>@lang('Payment Amount')</th>
                            <th>@lang('Total Due Amount')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributions as $item)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.distributioncommission.index', $item->id) }}" class="dropdown-item">
                                                    <i class="bi bi-card-list"></i> Commission Setup
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.distributioncommission.pdf', $item->id) }}" class="dropdown-item">
                                                    <i class="bi bi-download"></i> Commission PDF
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.distribution.statement', $item->id) }}" class="dropdown-item">
                                                    <i class="bi bi-card-list"></i> Statement
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.distribution.edit', $item->id) }}"
                                                    class="dropdown-item">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                    data-question="@lang('Are you sure you want to delete this item?')"
                                                    data-action="{{ route('admin.distribution.destroy', $item->id) }}"
                                                    class="dropdown-item confirmationBtn">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $item->name }}</td>
                                <td>{{ $item->mobile }}</td>
                                <td>{{ $item->orders->count() }}</td>
                                <td>{{ $item->orders->sum('grand_total') }}</td>
                                <td>{{ $item->distributionorderpayments->sum('amount') }}</td>                                
                                <td>{{ $item->receivable($item->id) }}</td>
                                <td>
                                    @if ($item->status == 'Active')
                                        <a href="{{ route('admin.distribution.status.change', $item->id) }}"
                                            onclick="return confirm('Are you sure! Inactive this Distribution')"
                                            class="btn btn-success btn-sm">Active</a>
                                    @else
                                        <a href="{{ route('admin.distribution.status.change', $item->id) }}"
                                            onclick="return confirm('Are you sure! Active this Distribution')"
                                            class="btn btn-danger btn-sm">Inactive</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="7">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

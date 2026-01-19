@extends('admin.layouts.app', ['title' => 'Leave Type list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                Leave Type list
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Short Code')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leavetypes as $LeaveType)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $LeaveType->name }} </td>
                                <td> {{ $LeaveType->short_code }} </td>
                                <td> <span
                                        class="btn btn-{{ statusButton($LeaveType->status) }} btn-sm">{{ $LeaveType->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.leavetype.edit', $LeaveType->id) }}">
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
                </table><!-- table end -->
            </div>
        </div>

    </div>
@endsection

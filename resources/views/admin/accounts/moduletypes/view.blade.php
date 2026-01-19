@extends('admin.layouts.app', ['title' => 'Module Type list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Module Type list
                <a href="{{ route('admin.moduletype.create') }}" class="btn btn-primary btn-sm float-end"><i
                        class="fa fa-plus"></i> Add
                    Module</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Short Code')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($moduletypes as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->name }}</td>
                                <td> {{ $item->short_code }}</td>
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
                                                <a href="{{ route('admin.moduletype.edit', $item->id) }}">
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

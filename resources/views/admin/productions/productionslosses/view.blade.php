@extends('admin.layouts.app', ['title' => __('Productions Loss List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Productions Loss List')
                <a href="{{ route('admin.productionloss.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> Add New</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Department')</th>
                            <th>@lang('Item')</th>
                            <th>@lang('Qty')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productions as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }} </td>
                                <td> {{ optional($item->department)->name }} </td>
                                <td> {{ optional($item->item)->name }} </td>
                                <td> {{ en2bn($item->qty) }}</td>
                                <td>
                                    <a href="{{ route('admin.productionloss.edit', $item->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>                                    
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

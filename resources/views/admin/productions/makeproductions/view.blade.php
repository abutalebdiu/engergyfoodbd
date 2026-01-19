@extends('admin.layouts.app', ['title' => __('Make Production Expense List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Make Production Expense List')
                <a href="{{ route('admin.makeproduction.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i>
                    @lang('Add New')
                </a>
            </h5>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (request()->start_date) value="{{ request()->start_date }}" @endif
                            class="form-control">
                    </div>

                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (request()->end_date) value="{{ request()->end_date }}" @endif class="form-control">
                    </div>

                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary "><i class="bi bi-search"></i>
                            @lang('Search')</button>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Department')</th>
                            <th>@lang('Quantity')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productions as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(date('d-m-Y',strtotime($item->date))) }} </td>
                                <td> {{ optional($item->department)->name }} </td>
                                <td> {{ en2bn($item->total_qty) }}</td>
                                <td class="d-flex gap-2">
                                    
                                    <a href="{{ route('admin.makeproduction.show',$item->date) }}?department_id={{$item->department_id}}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-eye"></i> @lang('Show')
                                    </a>
                                    <a href="{{ route('admin.makeproduction.edit',$item->date) }}?department_id={{$item->department_id}}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i> @lang('Edit')
                                    </a>
                                    <a href="{{ route('admin.makeproduction.show',$item->date) }}?department_id={{$item->department_id}}&type=pdf" class="btn btn-primary btn-sm">
                                        <i class="fa fa-download"></i> @lang('PDF')
                                    </a>
                                    <form method="post" action="{{ route('admin.makeproduction.destroy',$item->date) }}?department_id={{$item->department_id}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure! Delete this Data!')">  <i class="fa fa-trash"></i> Delete </button>
                                    </form>
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

 @extends('admin.layouts.app', ['title' => __('Productions List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('Productions List') }}
                <a href="{{ route('admin.dailyproduction.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> @lang('Add New Daily Production')</a>
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
            <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>@lang('SL No')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Total Qty')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyproductions as $item)
                                <tr>
                                    <td>{{ en2bn($loop->iteration) }}</td>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                    <td>{{ en2bn($item->total_qty) }}</td>
                                    <td class="d-flex gap-2">
                                        @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.edit'))
                                        <a href="{{ route('admin.dailyproduction.edit',$item->date) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i> @lang('Edit')
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.dailyproduction.show',$item->date) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i> @lang('Show')
                                        </a>
                                        
                                        <a href="{{ route('admin.dailyproduction.show',$item->date) }}?type=pdf" class="btn btn-info btn-sm">
                                            <i class="fa fa-download"></i> @lang('PDF')
                                        </a>
                                        
                                        
                                         @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.destroy'))
                                            <form method="post" action="{{ route('admin.dailyproduction.destroy',$item->date) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure! Delete this Data!')">  <i class="fa fa-trash"></i> Delete </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                
        </div>
    </div>
@endsection




@include('components.select2')

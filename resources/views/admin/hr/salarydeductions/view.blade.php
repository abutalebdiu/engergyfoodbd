@extends('admin.layouts.app',['title'=>'Salary Deduction List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Salary Deduction list
                <a href="{{ route('admin.salarydeduction.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Salary Deduction</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salarydeductions as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                <td> {{ number_format($item->amount) }}</td>
                                <td> {{ $item->note }}</td>
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td> {!! entry_info($item) !!} </td>
                                <td>
                                    <a href="{{ route('admin.salarydeduction.edit', $item->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>
                                    <a href="javascript:;" data-id="{{ $item->id }}"
                                        data-question="@lang('Are you sure you want to delete this item?')"
                                        data-action="{{ route('admin.salarydeduction.destroy', $item->id) }}"
                                        class="confirmationBtn">
                                        <i class="bi bi-trash"></i> @lang('Delete')
                                    </a>  
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

    <x-destroy-confirmation-modal />
@endsection

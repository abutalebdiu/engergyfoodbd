@extends('admin.layouts.app',['title'=>'Employee Bonus List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Employee Bonus list
                <a href="{{ route('admin.salarybonussetup.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> Add New Employee Bonus</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">

                   <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">@lang('Department')</option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"  data-employees="{{ $department->employees }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">@lang('Employee')</option>

                                @if(isset($employee) && $employee)
                                    @foreach ($employees ?? [] as $preemployee)
                                        <option value="{{ $preemployee->id }}"
                                            {{ $employee == $preemployee->id ? 'selected' : '' }}>
                                            {{ $preemployee->name }}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>


                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            Search</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            PDF</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            Excel</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Employee')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonuses as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ optional($item->employee)->name }} </td>
                                <td> {{ optional($item->month)->name }} - {{ optional($item->year)->name }} </td>
                                <td> {{ en2bn(number_format($item->amount)) }}</td>
                                <td> {{ $item->reason }}</td>
                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.salarybonussetup.edit', $item->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>
                                    <a href="javascript:;" data-id="{{ $item->id }}"
                                        data-question="@lang('Are you sure you want to delete this item?')"
                                        data-action="{{ route('admin.salarybonussetup.destroy', $item->id) }}"
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
                    <tfoot>
                        <tr>
                            <th colspan="4">@lang('Total')</th>
                            <th> {{ en2bn(number_format($bonuses->sum('amount'))) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection


@push('script')
    <script>
        $('[name=department_id]').on('change', function() {
            var employees = $(this).find('option:selected').data('employees');
            var option = '<option value="">Select Employee</option>';
            $.each(employees, function(index, value) {
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    value.name + "</option>";
            });

            $('select[name=employee_id]').html(option);
        }).change();
    </script>
@endpush

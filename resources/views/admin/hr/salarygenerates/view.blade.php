@extends('admin.layouts.app', ['title' => 'Employee Salary List'])
@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Employee Salary List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.salarygenerate.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Salary Process</a>
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
                            <th>@lang('Month')</th>
                            <th>@lang('Year')</th>
                            <th>@lang('Payable Salary')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salarygenerates as $salarygenerate)
                            <tr>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td>{{ $salarygenerate->month->name }}</td>
                                <td>{{ $salarygenerate->year->name }}</td>
                                <td>{{ en2bn(number_format($salarygenerate->total_amount,2,'.',',')) }}</td>
                                <td>{!! entry_info($salarygenerate) !!}</td>
                                <td>
                                    <a href="{{ route('admin.salarygenerate.show.detail') }}?month_id={{ $salarygenerate->month_id }}&year_id={{ $salarygenerate->year_id }}"
                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Show</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <x-destroy-confirmation-modal />
    @endsection

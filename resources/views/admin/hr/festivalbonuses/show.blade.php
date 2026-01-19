@extends('admin.layouts.app', ['title' => 'Festival Bonus Generate'])
@section('panel')
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Festival Bonus Generate</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.festivalbonus.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-list"></i> Festival Bonus List</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Employee Name</th>
                            <th rowspan="2">Bonus Date</th>
                            <th rowspan="2">Date of Joining</th>
                            <th colspan="3">Length of Service</th>
                            <th rowspan="2">Salary</th>
                            <th rowspan="2">Basic</th>
                            <th colspan="2">Bonus</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Days</th>
                            <th>(%)</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeesByDepartment as $departmentId => $employees)
                            @php
                                $departmentName = optional($employees->first()->department)->name;
                                $totalbonus  = 0;

                            @endphp
                            <tr>
                                <td colspan="12" class="font-weight-bold text-primary text-start">
                                    {{ $departmentName ?: 'No Department' }}
                                </td>
                            </tr>
                            @foreach ($employees as $item)
                                @php
                                    $festivalBonusDate = \Carbon\Carbon::parse($festivalbonus->date);
                                    $joinDate = \Carbon\Carbon::parse($item->joindate);
                                    $diff = $joinDate->diff($festivalBonusDate);

                                    $serviceLength = "{$diff->y} Years, {$diff->m} Months, {$diff->d} Days";
                                    $basicAmount = $item->salary / 2;
                                    $bonusamount = 0;

                                    if ($diff->y > 0) {
                                        $bonusamount = $basicAmount;
                                    } else {
                                        $bonusamount = round(($basicAmount / 12) * $diff->m);
                                    }

                                    $totalbonus += $bonusamount;

                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="text-align: left">{{ $item->name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($festivalbonus->date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->joindate)) }}</td>
                                    <td>{{ $diff->y }}</td>
                                    <td>{{ $diff->m }}</td>
                                    <td>{{ $diff->d }}</td>
                                    <td>{{ number_format($item->salary) }}</td>
                                    <td>{{ number_format($basicAmount) }}</td>
                                    <td>{{ $festivalbonus->percentage }}</td>
                                    <td>{{ number_format($bonusamount) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>@lang('Total')</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ number_format($totalbonus) }}</th>
                                <th></th>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

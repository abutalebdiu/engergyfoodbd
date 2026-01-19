@extends('admin.layouts.app', ['title' => 'Festival Bonus List'])
@section('panel')
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Festival Bonus List</h6>
        </div>
        <div class="ms-auto">
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="" method="get">
                <button type="submit" name="pdf" class="btn btn-primary btn-sm"> <i class="fa fa-download"></i> PDF
                    Download</button>
            </form>
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
                            <th rowspan="2">Status</th>
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
                        @php
                            $maintotalbonus = 0;
                        @endphp
                        @foreach ($festivalbonusdetailgroupes as $departmentId => $festivalbonusdetails)
                            @php
                                $departmentName = optional($festivalbonusdetails->first()->employee->department)->name;
                                $depttotalbonus = 0;
                            @endphp
                            <tr>
                                <td colspan="13" class="font-weight-bold text-primary text-start">
                                    {{ $departmentName ?: 'No Department' }}
                                </td>
                            </tr>
                            @foreach ($festivalbonusdetails as $item)
                                <tr>
                                    <td>{{ en2bn($loop->iteration) }}</td>
                                    <td style="text-align: left">{{ optional($item->employee)->name }}</td>
                                     <td>{{ en2bn(Date('d-m-Y', strtotime($item->festivalbonus->date))) }}</td>
                                    <td>{{ en2bn(Date('d-m-Y', strtotime(optional($item->employee)->joindate))) }}</td>

                                    @php
                                        $festivalBonusDate = \Carbon\Carbon::parse($item->festivalbonus->date);
                                        $joinDate = \Carbon\Carbon::parse(optional($item->employee)->joindate);
                                        $diff = $joinDate->diff($festivalBonusDate);
                                        $serviceLength = "{$diff->y} Years, {$diff->m} Months, {$diff->d} Days";

                                        $depttotalbonus += $item->amount;
                                        $maintotalbonus += $item->amount;
                                    @endphp

                                    <td>{{ en2bn(number_format($diff->y)) }}</td>
                                    <td>{{ en2bn(number_format($diff->m)) }}</td>
                                    <td>{{ en2bn(number_format($diff->d)) }}</td>
                                    <td>{{ en2bn(number_format($item->salary_amount)) }}</td>
                                    <td>{{ en2bn(number_format($item->basic_amount)) }}</td>
                                    <td>{{ en2bn(number_format($item->bonus_percentage)) }}</td>
                                    <td style="text-align: right">{{ en2bn(number_format($item->amount)) }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                            <a href="{{ route('admin.festivalbonusdetail.edit', $item->id) }}"
                                                class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i> Edit</a>
                                            <a href="{{ route('admin.festivalbonusdetail.show', $item->id) }}"
                                                class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> Make Payment</a>
                                            <a href="javascript:;" onclick="deleteItem({{ $item->id }})"
                                                class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="9">@lang('Total')</th>
                                <th>@lang('Total')</th>
                                <th style="text-align: right">{{ en2bn(number_format($depttotalbonus)) }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="9">@lang('Total')</th>
                            <th>@lang('Total')</th>
                            <th style="text-align: right">{{ en2bn(number_format($maintotalbonus)) }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

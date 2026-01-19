@extends('admin.layouts.app', ['title' => __('Department Wise Production Report')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Department Wise Production Report')

            </h6>
        </div>
        <div class="card-body">
            <form action="" class="my-3">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('Start Date')</label>
                            <input type="date" class="form-control" name="start_date"
                                value="{{ $start_date ? $start_date : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('End Date')</label>
                            <input type="date" class="form-control" name="end_date"
                                value="{{ $end_date ? $end_date : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('Dapartment')</label>
                            <select name="department_id" id="department_id" class="form-select select2">
                                <option value="">@lang('Select Department')</option>
                                @foreach ($departments as $department)
                                    <option
                                        @if (isset($department_id)) {{ $department_id == $department->id ? 'selected' : '' }} @endif
                                        value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="d-flex">
                            <div class="form-group ">
                                <button class="btn btn-primary mt-4"> <i class="fa fa-search"></i>
                                    @lang('Search')</button>
                            </div>
                            <div class="form-group ms-2">
                                <button class="btn btn-info mt-4" name="export" value="export"> <i class="fa fa-print"></i>
                                    @lang('Export Excel')</button>
                            </div>
                            <div class="form-group ms-2">
                                <button class="btn btn-info mt-4" name="export" value="pdf"> <i class="fa fa-print"></i>
                                    @lang('Export PDF')</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hovered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Total Received Qty')</th>
                            <th>@lang('Total Received Cost')</th>
                            <th>@lang('PP Cost')</th>
                            <th>@lang('Box Cost')</th>
                            <th>@lang('Striker Cost')</th>
                            <th>@lang('Total Cost')</th>
                            <th>@lang('Production Qty')</th>
                            <th>@lang('Production Gram')</th>
                            <th>@lang('Production Price')</th>
                            <th>@lang('Profit/Loss')</th>
                            <th>@lang('Profit/Loss') (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_r_qty = 0;
                            $total_r_cost = 0;
                            $total_pp__cost = 0;
                            $total_box__cost = 0;
                            $total_striker__cost = 0;
                            $total__cost = 0;
                            $total_p_qty = 0;
                            $total_p_gram = 0;
                            $total_p_cost = 0;
                            $total_profit_loss = 0;
                            $total_profit_loss_percentage = 0;
                        @endphp
                        @forelse($items as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item['name'] }} </td>
                                <td> {{ en2bn(number_format($item['total_received_qty'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_received_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_pp_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_box_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_striker_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['total_cost'], 2)) }} </td>
                                <td> {{ en2bn(number_format($item['production_qty'], 2)) }} </td>
                                <td> {{ en2bn($item['production_gram']) }}  <br>   ({{ en2bn(number_format($item['production_gram']/1000,2))  }} KG)</td>
                                <td> {{ en2bn(number_format($item['production_price'], 2)) }} </td>
                                <td class="{{ $item['profit_or_loss'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ en2bn(number_format($item['profit_or_loss'], 2)) }} </td>
                                <td class="{{ $item['profit_or_loss_percentage'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ en2bn(number_format($item['profit_or_loss_percentage'], 2)) }}% </td>
                            </tr>

                            @php
                                $total_r_qty += $item['total_received_qty'];
                                $total_r_cost += $item['total_received_cost'];
                                $total_pp__cost += $item['total_pp_cost'];
                                $total_box__cost += $item['total_box_cost'];
                                $total_striker__cost += $item['total_striker_cost'];
                                $total__cost += $item['total_cost'];
                                $total_p_qty += $item['production_qty'];
                                $total_p_gram += $item['production_gram'];
                                $total_p_cost += $item['production_price'];
                                $total_profit_loss += $item['profit_or_loss'];
                                $total_profit_loss_percentage += $item['profit_or_loss_percentage'];
                            @endphp
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>@lang('Total')</th>
                            <th>{{ en2bn(number_format($total_r_qty, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_r_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_pp__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_box__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_striker__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total__cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_p_qty, 2)) }}</th>
                            <th>{{ en2bn($total_p_gram) }} গ্রাম <br> ({{ en2bn(number_format($total_p_gram/1000,2))  }} KG)</th>
                            <th>{{ en2bn(number_format($total_p_cost, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_profit_loss, 2)) }}</th>
                            <th>{{ en2bn(number_format($total_profit_loss_percentage / count($items), 2)) }}%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <x-destroy-confirmation-modal />
@endsection

@extends('admin.layouts.app', ['title' => __('Production Detail')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('Production Detail') }} in {{ $date }}
                <a href="{{ route('admin.dailyproduction.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="bi bi-plus"></i> @lang('Add New Daily Production')</a>

                <a href="{{ route('admin.dailyproduction.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-list"></i> @lang('Daily Production List')</a>
                        
                <a href="{{ route('admin.dailyproduction.show',$date) }}?type=pdf" class="btn btn-info btn-sm float-end">
                    <i class="fa fa-download"></i> @lang('PDF')
                </a>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-9">
                     <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr class="border-bottom">
                                <th>@lang('SL No')</th>
                                <th>@lang('Product')</th>
                                <th>@lang('Unit Price')</th>
                                <th>@lang('Quantity')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('PP Cost')</th>
                                <th>@lang('Box Cost')</th>
                                <th>@lang('Striker Cost')</th>
                                <th>@lang('Weight')(গ্রাম)</th>
                                <th>@lang('Total Weight')(গ্রাম)</th>
                                <th>@lang('Total Weight (KG)')</th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            @php
                                $totalqty = 0;
                                $totalmainamount = 0;
                                $totalpp_cost = 0;
                                $totalbox_cost = 0;
                                $totalstriker_cost = 0;
                                $total_weight_gram = 0;
                                $total_weight_kg = 0;
                            @endphp
                    
                            @foreach ($dailyproductions as $departmentId => $productions)
                                @php
                                    // reset department-wise subtotal
                                    $dept_total_amount = 0;
                                    $dept_total_weight_gram = 0;
                                    $dept_total_weight_kg = 0;
                                @endphp
                    
                                <tr>
                                    <td colspan="11" style="font-weight: bold; text-align: left;">
                                        {{ optional($productions->first()->product)->department->name ?? 'Unknown Department' }}
                                    </td>
                                </tr>
                    
                                @foreach ($productions as $key => $dailyproduction)
                                    @php
                                        $line_amount = $dailyproduction->qty * $dailyproduction->product->sale_price;
                                        $line_weight_gram = optional($dailyproduction->product)->weight_gram * $dailyproduction->qty;
                                        $line_weight_kg = $line_weight_gram / 1000;
                    
                                        $dept_total_amount += $line_amount;
                                        $dept_total_weight_gram += $line_weight_gram;
                                        $dept_total_weight_kg += $line_weight_kg;
                                    @endphp
                    
                                    <tr>
                                        <td>{{ en2bn($loop->iteration) }}</td>
                                        <td style="text-align: left">{{ optional($dailyproduction->product)->name }}</td>
                                        <td>{{ en2bn($dailyproduction->product->sale_price) }}</td>
                                        <td>{{ en2bn($dailyproduction->qty) }}</td>
                                        <td>{{ en2bn(number_format($line_amount, 2, '.', ',')) }}</td>
                                        <td>{{ en2bn($dailyproduction->pp_cost) }}</td>
                                        <td>{{ en2bn($dailyproduction->box_cost) }}</td>
                                        <td>{{ en2bn($dailyproduction->striker_cost) }}</td>
                                        <td>{{ en2bn(optional($dailyproduction->product)->weight_gram) }}</td>
                                        <td>{{ en2bn($line_weight_gram) }}</td>
                                        <td>{{ en2bn(number_format($line_weight_kg, 2)) }}</td>
                                    </tr>
                                @endforeach
                    
                                {{-- Department subtotal --}}
                                <tr style="font-weight: bold; background-color: #f8f9fa;">
                                    <th colspan="2">@lang('Total for ')
                                        {{ optional($productions->first()->product)->department->name ?? 'Unknown Department' }}
                                    </th>
                                    <td></td>
                                    <td>{{ en2bn(number_format($productions->sum('qty'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($dept_total_amount, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('pp_cost'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('box_cost'), 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($productions->sum('striker_cost'), 2, '.', ',')) }}</td>
                                    <td></td>
                                    <td>{{ en2bn(number_format($dept_total_weight_gram, 2, '.', ',')) }}</td>
                                    <td>{{ en2bn(number_format($dept_total_weight_kg, 2, '.', ',')) }}</td>
                                </tr>
                    
                                @php
                                    // accumulate overall totals
                                    $totalqty += $productions->sum('qty');
                                    $totalmainamount += $dept_total_amount;
                                    $totalpp_cost += $productions->sum('pp_cost');
                                    $totalbox_cost += $productions->sum('box_cost');
                                    $totalstriker_cost += $productions->sum('striker_cost');
                                    $total_weight_gram += $dept_total_weight_gram;
                                    $total_weight_kg += $dept_total_weight_kg;
                                @endphp
                            @endforeach
                        </tbody>
                    
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #e9ecef;">
                                <th colspan="2">@lang('Grand Total')</th>
                                <th></th>
                                <th>{{ en2bn(number_format($totalqty, 0, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalmainamount, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalpp_cost, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalbox_cost, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($totalstriker_cost, 2, '.', ',')) }}</th>
                                <th></th>
                                <th>{{ en2bn(number_format($total_weight_gram, 2, '.', ',')) }}</th>
                                <th>{{ en2bn(number_format($total_weight_kg, 2, '.', ',')) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection




@include('components.select2')

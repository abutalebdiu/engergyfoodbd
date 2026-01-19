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
            <form action="">
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
                            <div class="form-group">
                                <button class="btn btn-primary mt-4"> <i class="fa fa-search"></i>
                                    @lang('Search')</button>
                            </div>
                            <div class="form-group ms-2">
                                <button class="btn btn-info mt-4" name="export" value="export"> <i class="fa fa-print"></i>
                                    @lang('Export Excel')</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>


            @if ($searching == 'Yes')
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('Sl')</th>
                                        <th>@lang('Product Name')</th>
                                        <th>@lang('Yeast')</th>
                                        @foreach ($items as $item)
                                            <th class="text-start">{{ $loop->iteration }} - {{ $item->name }}</th>
                                            <th>@lang('Price')</th>
                                            <th>@lang('Total')</th>
                                            <th>@lang('Receive Qty')</th>
                                            <th>@lang('Cost')</th>
                                        @endforeach
                                        <th>@lang('Total Yeast Cost')</th>
                                        <th>@lang('Receive Item QTY')</th>
                                        <th>@lang('Receive Item Cost')</th>
                                        <th>@lang('PP Cost')</th>
                                        <th>@lang('Box Cost')</th>
                                        <th>@lang('Striker Cost')</th>
                                        <th>@lang('Total Cost')</th>
                                        <th>@lang('Production QTY')</th>
                                        <th>@lang('Production Amount')</th>
                                        <th>@lang('Profit/Loss')</th>
                                        <th>@lang('Profit/Loss %')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalItemQty = [];
                                        $totalItemCost = [];
                                        $grandTotalProductCost = 0;

                                        $receiveqty = 0;
                                        $receiveamount = 0;

                                        $dailyproductionqty = 0;
                                        $dailyproductionamount = 0;

                                    @endphp
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start">{{ $product->name }}</td>
                                            <td>{{ en2bn($product->yeast) }}</td>
                                            @php
                                                $totalProductCost = 0;
                                            @endphp
                                            @foreach ($items as $item)
                                                @php
                                                    $recipe = App\Models\Product\ProductRecipe::where(
                                                        'product_id',
                                                        $product->id,
                                                    )
                                                        ->where('item_id', $item->id)
                                                        ->first();
                                                    $qty = $recipe ? $recipe->qty : 0;
                                                    $cost = $qty * $item->price;

                                                    // Calculate totals per item
                                                    $totalItemQty[$item->id] = ($totalItemQty[$item->id] ?? 0) + $qty;
                                                    $totalItemCost[$item->id] =
                                                        ($totalItemCost[$item->id] ?? 0) + $cost;

                                                    // Calculate total cost per product
                                                    $totalProductCost += $cost;
                                                @endphp
                                                <td>{{ en2bn($qty) }}</td>
                                                <td>{{ en2bn($item->price) }}</td>
                                                <td>{{ en2bn(number_format($cost, 2)) }}</td>
                                                @if ($key == 0)
                                                    <td rowspan="{{ $products->count() }}">
                                                        {{ en2bn($item->makeproductionqtysum($start_date, $end_date, $item->id, $department_id)) }}
                                                        @php
                                                            $receiveqty += $item->makeproductionqtysum(
                                                                $start_date,
                                                                $end_date,
                                                                $item->id,
                                                                $department_id,
                                                            );
                                                        @endphp
                                                    </td>
                                                @endif
                                                @if ($key == 0)
                                                    <td rowspan="{{ $products->count() }}">
                                                        {{ en2bn(round($item->price * $item->makeproductionqtysum($start_date, $end_date, $item->id, $department_id), 2)) }}

                                                        @php
                                                            $receiveamount +=
                                                                $item->price *
                                                                $item->makeproductionqtysum(
                                                                    $start_date,
                                                                    $end_date,
                                                                    $item->id,
                                                                    $department_id,
                                                                );
                                                        @endphp
                                                    </td>
                                                @endif
                                            @endforeach
                                            <td>{{ en2bn(number_format($totalProductCost, 2)) }}</td>
                                            @php
                                                $grandTotalProductCost += $totalProductCost;
                                            @endphp
                                            @php
                                                $dailyproductions = App\Models\DailyProduction::where(
                                                    'product_id',
                                                    $product->id,
                                                )
                                                    ->whereBetween('date', [$start_date, $end_date])
                                                    ->get();

                                                $dailyproductionqty += $dailyproductions->sum('qty');
                                                $dailyproductionamount +=
                                                    $dailyproductions->sum('qty') * $product->sale_price;
                                            @endphp

                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_received_qty, 2)) }}
                                                </td>
                                            @endif


                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_received_cost, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_pp_cost, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_box_cost, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_striker_cost, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($total_cost, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($production_qty, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($production_price, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($profit_or_loss, 2)) }}
                                                </td>
                                            @endif
                                            @if ($key == 0)
                                                <td rowspan="{{ $products->count() }}">
                                                    {{ en2bn(number_format($profit_or_loss_percentage, 2)) }}%
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">@lang('Total')</th>
                                        @foreach ($items as $item)
                                            <th>{{ en2bn($totalItemQty[$item->id] ?? 0) }}</th>
                                            <th></th>
                                            <th>{{ en2bn(number_format($totalItemCost[$item->id] ?? 0, 2)) }}</th>
                                            <td></td>
                                            <td></td>
                                        @endforeach
                                        <th></th>
                                        <th>{{ en2bn(number_format($total_received_qty, 2)) }}</th>
                                        <th>{{ en2bn(number_format($total_received_cost, 2)) }}</th>
                                        <th>{{ en2bn(number_format($total_pp_cost, 2)) }}</th>
                                        <th>{{ en2bn(number_format($total_box_cost, 2)) }}</th>
                                        <th>{{ en2bn(number_format($total_striker_cost, 2)) }}</th>
                                        <th>{{ en2bn(number_format($total_cost, 2)) }}</th>
                                        <th>{{ en2bn(number_format($production_qty, 2)) }}</th>
                                        <th>{{ en2bn(number_format($production_price, 2)) }}</th>
                                        <th class="{{ $profit_or_loss > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ en2bn(number_format($profit_or_loss, 2)) }}
                                        </th>
                                        <th class="{{ $profit_or_loss_percentage > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ en2bn(number_format($profit_or_loss_percentage, 2)) }}%
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@include('components.select2')

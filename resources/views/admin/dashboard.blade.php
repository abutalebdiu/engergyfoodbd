@extends('admin.layouts.app', ['title' => 'Admin Dashboard'])


@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use App\Models\Order\Order;
@endphp
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <input type="date" name="start_date"
                                    @if (isset($start_date)) value="{{ $start_date }}" @endif
                                    class="form-control">
                            </div>
                            <div class="col-12 col-md-3">
                                <input type="date" name="end_date"
                                    @if (isset($end_date)) value="{{ $end_date }}" @endif
                                    class="form-control">
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" name="search" class="btn btn-primary "><i class="bi bi-search"></i>
                                    @lang('Search')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::guard('admin')->user()->hasPermission('admin.dashboard.statistics'))
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-4">
            <div class="col">
                <a href="{{ route('admin.quotation.index') }}">
                    <div class="card rounded-4 bg-purple">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <h4 class="mb-0 text-white">{{ en2bn($totalQuotationsCount) }}</h4>
                                    <p class="mb-1 text-white">Quotation Today</p>
                                </div>
                                <div class="ms-auto fs-3 text-white">
                                    <i class="fa fa-coins"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.quotation.index') }}">
                    <div class="card rounded-4 bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <h4 class="mb-0 text-white">{{ en2bn($totalQuotationsAmount) }}</h4>
                                    <p class="mb-1 text-white">@lang('Quotation Amount Today')</p>
                                </div>
                                <div class="ms-auto fs-3 text-white">
                                    <i class="fa fa-money-bill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('admin.order.index') }}">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Order')</p>
                                    <h4 class="mb-0 text-success">{{ en2bn($orders) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.order.index') }}">
                    <div class="border-0 card radius-10 border-start border-pink border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Order Amount')</p>
                                    <h4 class="mb-0 text-pink">{{ en2bn(number_format($orderamounts, 2, '.', ',')) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-pink">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.orderpayment.index') }}">
                    <div class="border-0 card radius-10 border-start border-info border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Collection')</p>
                                    <h4 class="mb-0 text-info"> {{ en2bn(number_format($totalpayments, 2, '.', ',')) }}
                                    </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-info">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.order.index') }}">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Due Amount')</p>
                                    <h4 class="mb-0 text-success">
                                        {{ en2bn(number_format($orderamounts - $totalpayments, 2, '.', ',')) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col">
                <a href="{{ route('admin.orderpayment.index') }}">
                    <div class="border-0 card radius-10 border-start border-info border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Today Collection')</p>
                                    <h4 class="mb-0 text-info"> {{ en2bn(number_format($todaypayments, 2, '.', ',')) }}
                                    </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-info">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.orderpayment.index') }}">
                    <div class="border-0 card radius-10 border-start border-info border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Monthly Collection')</p>
                                    <h4 class="mb-0 text-info">{{ en2bn(number_format($monthlypayments, 2, '.', ',')) }}
                                    </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-info">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.orderpayment.index') }}">
                    <div class="border-0 card radius-10 border-start border-info border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Yearly Collection')</p>
                                    <h4 class="mb-0 text-info">{{ en2bn(number_format($yearlypayments, 2, '.', ',')) }}
                                    </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-info">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.orderpayment.index') }}">
                    <div class="border-0 card radius-10 border-start border-info border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Collection')</p>
                                    <h4 class="mb-0 text-info"> {{ en2bn(number_format($totalpayments, 2, '.', ',')) }}
                                    </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-info">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.expense.index') }}">
                    <div class="border-0 card radius-10 border-start border-purple border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Today Expense')</p>
                                    <h4 class="mb-0 text-purple"> {{ en2bn($todayexpenses) }} </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-purple">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.expense.index') }}">
                    <div class="border-0 card radius-10 border-start border-purple border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Monthly Expense')</p>
                                    <h4 class="mb-0 text-purple">{{ en2bn($monthlyexpenses) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-purple">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.expense.index') }}">
                    <div class="border-0 card radius-10 border-start border-purple border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Yearly Expense')</p>
                                    <h4 class="mb-0 text-purple">{{ en2bn($yearlyexpenses) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-purple">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.expense.index') }}">
                    <div class="border-0 card radius-10 border-start border-purple border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Expense')</p>
                                    <h4 class="mb-0 text-purple">{{ en2bn($totalexpenses) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-purple">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Supplier Advanced')</p>
                                    <h4 class="mb-0 text-success"> {{ en2bn($todaysupplierpayments) }} </h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Monthly Supplier Payment')</p>
                                    <h4 class="mb-0 text-success">{{ en2bn($monthlysupplierpayments) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Yearly Supplier Payment')</p>
                                    <h4 class="mb-0 text-success">{{ en2bn($yearlysupplierpayments) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="">
                    <div class="border-0 card radius-10 border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Supplier Payment')</p>
                                    <h4 class="mb-0 text-success">{{ en2bn($totalsupplierpayments) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-success">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="{{ route('admin.staff.index') }}">
                    <div class="border-0 card radius-10 border-start border-primary border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Users')</p>
                                    <h4 class="mb-0 text-primary">{{ en2bn($admins) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.customers.all') }}">
                    <div class="border-0 card radius-10 border-start border-primary border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Customers')</p>
                                    <h4 class="mb-0 text-primary">{{ en2bn($customers) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.suppliers.all') }}">
                    <div class="border-0 card radius-10 border-start border-primary border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Suppliers')</p>
                                    <h4 class="mb-0 text-primary">{{ en2bn($suppliers) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.employee.index') }}">
                    <div class="border-0 card radius-10 border-start border-primary border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <p class="mb-1">@lang('Total Employees')</p>
                                    <h4 class="mb-0 text-primary">{{ en2bn($employees) }}</h4>
                                </div>
                                <div class="text-white ms-auto widget-icon bg-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif

    @if (Auth::guard('admin')->user()->hasPermission('admin.dashboard.chart-reports'))
        <div class="row">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        @php
                            $sales = Order::select(DB::raw('DATE(date) as date'), DB::raw('SUM(net_amount) as amount'))
                                ->whereBetween('date', [$start_date, $end_date])
                                ->groupBy(DB::raw('DAY(date)'))
                                ->orderBy(DB::raw('DAY(date)'))
                                ->get();

                            $sales = $formattedDates->map(function ($date) use ($sales) {
                                $match = $sales->firstWhere('date', $date);
                                return [
                                    'date' => (new DateTime($date))->format('d-m'),
                                    'amount' => $match ? $match->amount : 0,
                                ];
                            });

                            $labels = $sales->pluck('date')->toArray();
                            $values = $sales->pluck('amount')->toArray();
                        @endphp



                        <x-floating-bar-chart :title="__('Daily Sales' . '-' . Carbon::now()->format('F Y'))" :labels="$labels" :values="$values" />

                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        @php

                            $sales = App\Models\Order\Order::select(
                                \Illuminate\Support\Facades\DB::raw('MONTH(date) as month_number'),
                                \Illuminate\Support\Facades\DB::raw('MONTHNAME(date) as month'),
                                \Illuminate\Support\Facades\DB::raw('SUM(grand_total) as amount'),
                            )
                                // ->whereBetween('date', [$start_date, $end_date])
                                ->groupBy(\Illuminate\Support\Facades\DB::raw('MONTH(date)'))
                                ->orderBy(\Illuminate\Support\Facades\DB::raw('MONTH(date)'))
                                ->get();

                            $allMonths = collect(range(1, 12))->map(function ($monthNumber) {
                                return [
                                    'month_number' => $monthNumber,
                                    'month' => Carbon::create()->month($monthNumber)->format('F'),
                                    'amount' => 0,
                                ];
                            });

                            $sales = $allMonths->map(function ($month) use ($sales) {
                                $match = $sales->firstWhere('month_number', $month['month_number']);
                                return [
                                    'month' => $month['month'],
                                    'amount' => $match ? $match->amount : 0,
                                ];
                            });

                            $labels = $sales->pluck('month')->toArray();
                            $values = $sales->pluck('amount')->toArray();
                        @endphp
                        <x-floating-bar-chart :title="__('Monthly Sales')" :labels="$labels" :values="$values" />
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        @php

                            $itemorders = App\Models\ItemOrder::select(
                                DB::raw('DATE(date) as date'),
                                DB::raw('SUM(totalamount) as t_amount'),
                            )
                                ->whereBetween('date', [$start_date, $end_date])
                                ->groupBy(DB::raw('DAY(date)'))
                                ->orderBy(DB::raw('DAY(date)'))
                                ->get();

                            $reports = $formattedDates->map(function ($date) use ($itemorders) {
                                $match = $itemorders->firstWhere('date', $date);
                                return [
                                    'date' => (new DateTime($date))->format('d-m'),
                                    'reports' => $match ? $match->t_amount : 0,
                                ];
                            });

                            $labels = $reports->pluck('date')->toArray();
                            $values = $reports->pluck('reports')->toArray();
                        @endphp


                        <x-floating-bar-chart :title="__('Item Order')" :labels="$labels" :values="$values" />

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

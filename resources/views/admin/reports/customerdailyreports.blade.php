@extends('admin.layouts.app', ['title' => __('Customer Daily Sales Reports')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Customer Daily Sales Reports')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" name="date" class="form-control"
                                @if (isset($date)) value="{{ $date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
               
                    <div class="row mt-4">
                        <div class="col-12">
                            <p class=" mt-5">@lang('Date'): @if (isset($date)) {{ en2bn(Date('d-m-Y', strtotime($date))) }} @endif</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('ID')</th>
                                            <th>@lang('Name of Distributor')</th>
                                            <th>@lang('Address')</th>
                                            <th>@lang('Marketer')</th>
                                            <th>@lang('Commission')</th>
                                            <th>@lang('Commission Type')</th>
                                            <th>@lang('Previous Dues')</th>
                                            <th>@lang('Challan Amount')</th>
                                            <th>@lang('Good Returns')</th>
                                            <th>@lang('Damage Returns')</th>
                                            <th>@lang('Net Challan')</th>
                                            <th>@lang('Total Commission')</th>
                                            <th>@lang('Order Dues')</th>
                                            <th>@lang('Today Collection')</th>
                                            <th>@lang('Today Dues')</th>
                                             <th>@lang('Due Collection')</th>
                                            <th>@lang('Net Dues')</th>
                                            <th>@lang('Net Sales This Months')</th>
                                            <th>@lang('Remarks')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalprevious_due  = 0;
                                            $totalsub_total     = 0;
                                            $totalreturn_amount = 0;
                                            $totalproductDamage = 0;
                                            $totalnet_amount    = 0;
                                            $totalcommission    = 0;
                                            $totalgrand_total   = 0;
                                            $totalpaid_amount   = 0;
                                            $totalorder_due     = 0;
                                            $totalduePayments   = 0;
                                            $overalldue         = 0;
                                            $totalmonthly_sales = 0;
                                        @endphp
                                        
                                        
                                       @foreach($customers as $customer)
                                            <tr>
                                                <td style="text-align:left">{{ en2bn($customer->uid) }}</td>
                                                <td style="text-align:left">{{ $customer->name }}</td>
                                                <td style="text-align:left">{{ $customer->address }}</td>
                                                <td style="text-align:left">{{ $customer->reference?->name }}</td>
                                                <td style="text-align:left">{{ en2bn($customer->commission) }}</td>
                                                <td style="text-align:left">{{ __($customer->commission_type) }}</td>
                                                @php
                                                    $order = $customer->orders->first(); // Get the order for the specific date
                                                @endphp
                                                @if($order)
                                                
                                                @php
                                                    $totalprevious_due  += $order->previous_due;
                                                    $totalsub_total     += $order->sub_total;
                                                    $totalreturn_amount += $order->return_amount;
                                                    $totalnet_amount    += $order->net_amount;
                                                    $totalcommission    += $order->commission;
                                                    $totalgrand_total   += $order->grand_total;
                                                    $totalpaid_amount   += $order->paid_amount;
                                                    $totalorder_due     += $order->order_due;
                                                    $overalldue         += $order->customer_due-$customer->duePayments->sum('amount');
                                                @endphp
                                        
                                        
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->previous_due,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->sub_total,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->return_amount,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->productDamage->sum('total_amount'),2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->net_amount,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->commission,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->grand_total,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->paid_amount,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->order_due,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->duePayments->sum('amount'),2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($order->customer_due-$customer->duePayments->sum('amount'),2)) }}</td>
                                                @else
                                                
                                                @php
                                                    $totalprevious_due  += $customer->receivable($customer->id);
                                                     $overalldue        += $customer->receivable($customer->id);
                                                @endphp
                                                
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(($customer->receivable($customer->id) + $customer->duePayments->sum('amount')),2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->productDamage->sum('total_amount'),2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format(0,2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->duePayments->sum('amount'),2)) }}</td>
                                                    <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->receivable($customer->id),2)) }}</td>
                                                @endif
                                                
                                                @php
                                                    $totalproductDamage += $customer->productDamage->sum('total_amount');
                                                    $totalduePayments   += $customer->duePayments->sum('amount');
                                                    $totalmonthly_sales += $customer->monthly_sales;
                                                @endphp
                                                
                                                
                                                <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($customer->monthly_sales,2)) }}</td>
                                                <td></td>
                                            </tr>
                                            @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">@lang('Total')</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalprevious_due,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalsub_total,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalreturn_amount,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalproductDamage,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalnet_amount,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalcommission,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalgrand_total,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalpaid_amount,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalorder_due,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalduePayments,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($overalldue,2)) }}</th>
                                            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalmonthly_sales,2)) }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                         
                    </div>
                </form>
            @endif
 

        </div>

    </div>
@endsection

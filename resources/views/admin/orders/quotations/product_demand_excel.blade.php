<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Quotation Product Demand List')</title>
</head>

<body>
    <div class="wrapper">
        <div class="print-header" style="text-align: center;margin-bottom:15px">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>

        <div class="quotation-title">
            <p style="margin: 0;padding:0;width:100%;text-align:right">
                @lang('Date') : {{ en2bn(Date('d-m-Y', strtotime($date))) }}
            </p>
            <p style="margin: 0;padding:0;width:100%;text-align: center">
                <span style="">@lang('Product Demand List')</span>
            </p>
        </div>
        <div class="product-detail">
            @if ($searching == 'Yes')
                <table border="1">
                    <thead>
                        <tr>
                            <th>@lang('SL No')</th>
                            <th>@lang('Product')</th>
                            @if ($type == 'WC')
                                @foreach ($customers as $customer)
                                    <th>{{ $customer->name }}</th>
                                @endforeach
                            @endif
                            <th>@lang('Total')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotal = 0;
                            $customerTotals = array_fill_keys($customers->pluck('id')->toArray(), 0);
                        @endphp

                        @foreach ($products_by_department as $departmentId => $productsInDepartment)
                            @php
                                $departmentName =
                                    $productsInDepartment->first()->first()->department_name ?? 'No Department';
                            @endphp
                            <tr class="table-secondary">
                                <td colspan="{{ 3 + count($customers) }}" style="text-align: left">
                                    <strong>{{ $departmentName }}</strong>
                                </td>
                            </tr>

                            @foreach ($productsInDepartment as $productId => $productGroup)
                                @php $productTotal = $productGroup->sum('total_qty'); @endphp
                                <tr>
                                    <td>{{ en2bn($loop->iteration) }}</td>
                                    <td style="text-align: left">{{ $productGroup->first()->name }}</td>

                                    @foreach ($customers as $customer)
                                        @php
                                            $customerOrder = $productGroup
                                                ->where('customer_id', $customer->id)
                                                ->first();
                                            $qty = $customerOrder ? $customerOrder->total_qty : 0;
                                            $customerTotals[$customer->id] += $qty;
                                        @endphp
                                        @if ($type == 'WC')
                                            <td>{{ en2bn($qty) }}</td>
                                        @endif
                                    @endforeach

                                    <td>{{ en2bn($productTotal) }}</td>
                                    @php $grandTotal += $productTotal; @endphp
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>@lang('Total')</th>
                            @foreach ($customers as $customer)
                                @if ($type == 'WC')
                                    <th>{{ en2bn($customerTotals[$customer->id]) }}</th>
                                @endif
                            @endforeach
                            <th>{{ en2bn(number_format($grandTotal, 0, '.', ',')) }}</th>
                        </tr>
                    </tfoot>
                </table>


            @endif
        </div>

    </div>
</body>

</html>

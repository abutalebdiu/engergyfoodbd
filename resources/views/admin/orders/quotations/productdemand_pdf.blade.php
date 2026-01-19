<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Quotation Product Demand List')</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 20pt 40pt;
        }

        .print-header h4 {
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .page-break {
            page-break-after: always;
        }

        /* .divulombo {
            writing-mode: vertical-lr;
            border: solid black 1px;
            display: inline-block;
            height: 350px;
            width: 300px;
            margin: 5px;

        }
        .divulombo span{
            text-orientation: mixed;
        }; */
    </style>
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
                @if ($type == 'WC')
                     @foreach ($customers->chunk(4) as $chunkIndex => $customerChunk)
                    @php
                        // Reset for each table
                        $grandTotal = 0;
                        $customerTotals = array_fill_keys($customerChunk->pluck('id')->toArray(), 0);
                    @endphp
                
                    <table border="1" style="width:100%; margin-bottom:20px;">
                        <thead>
                            <tr>
                                <th>@lang('SL No')</th>
                                <th>@lang('Product')</th>
                                @if ($type == 'WC')
                                    @foreach ($customerChunk as $customer)
                                        <th class="divulombo">
                                            <span>{{ $customer->name }}</span>
                                        </th>
                                    @endforeach
                                @endif
                                <th>@lang('Total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products_by_department as $departmentId => $productsInDepartment)
                                @php
                                    $departmentName = $productsInDepartment->first()->first()->department_name ?? 'No Department';
                                @endphp
                                <tr class="table-secondary">
                                    @if ($type == 'WC')
                                        <td colspan="{{ 3 + count($customerChunk) }}">
                                            <strong>{{ $departmentName }}</strong>
                                        </td>
                                    @else
                                        <td colspan="3">
                                            <strong>{{ $departmentName }}</strong>
                                        </td>
                                    @endif
                                </tr>
                
                                @foreach ($productsInDepartment as $productId => $productGroup)
                                    @php
                                        $productTotal = $productGroup->sum('total_qty');
                                        $grandTotal += $productTotal;
                                    @endphp
                                    <tr>
                                        <td>{{ en2bn($loop->iteration) }}</td>
                                        <td style="text-align: left">{{ $productGroup->first()->name }}</td>
                
                                        @foreach ($customerChunk as $customer)
                                            @php
                                                $customerOrder = $productGroup->where('customer_id', $customer->id)->first();
                                                $qty = $customerOrder ? $customerOrder->total_qty : 0;
                                                $customerTotals[$customer->id] += $qty;
                                            @endphp
                                            @if ($type == 'WC')
                                                <td>{{ en2bn($qty) }}</td>
                                            @endif
                                        @endforeach
                
                                        <td>{{ en2bn($productTotal) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>@lang('Total')</th>
                                @foreach ($customerChunk as $customer)
                                    @if ($type == 'WC')
                                        <th>{{ en2bn($customerTotals[$customer->id]) }}</th>
                                    @endif
                                @endforeach
                                <th>{{ en2bn(number_format($grandTotal, 0, '.', ',')) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                @endforeach

                @else
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
            @endif
        </div>
    </div>
</body>

</html>

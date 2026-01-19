 <table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('SL No')</th>
            <th>@lang('Product')</th>
            @if ($type == 'WC')
                @foreach ($customers as $customer)
                    <th>{{ $customer->name }}</th>
                @endforeach
            @endif
            <th>@lang('Total Qty')</th>
            <th>@lang('Total Amount')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $departmentId => $productGroup)
            @php
                $departmentName = $productGroup->first()->department_name;
            @endphp
            <tr>
                <th colspan="{{ 4 + count($customers) }}" style="text-align: left">
                    @lang('Department'):
                    {{ $departmentName }}</th>
            </tr>

            @foreach ($productGroup->groupBy('product_id') as $productId => $productItems)
                <tr>
                    <td>{{ en2bn($loop->iteration) }}</td>
                    <td style="text-align: left">{{ $productItems->first()->name }}</td>

                    @foreach ($customers as $customer)
                        @php
                            $customerOrder = $productItems
                                ->where('customer_id', $customer->id)
                                ->first();
                            $qty = $customerOrder ? $customerOrder->total_qty : 0;
                        @endphp
                        @if ($type == 'WC')
                            <td>{{ en2bn($qty) }}</td>
                        @endif
                    @endforeach

                    <td>{{ en2bn($productItems->sum('total_qty')) }}</td>
                    <td>{{ en2bn($productItems->sum('total_amount')) }}</td>
                </tr>
            @endforeach

            {{-- Department subtotal --}}
            <tr style="font-weight: bold">
                <td colspan="2">@lang('Subtotal')</td>
                @foreach ($customers as $customer)
                    @php
                        $totalQtyByCustomer = $productGroup
                            ->where('customer_id', $customer->id)
                            ->sum('total_qty');
                    @endphp
                    @if ($type == 'WC')
                        <td>{{ en2bn($totalQtyByCustomer) }}</td>
                    @endif
                @endforeach
                <td>{{ en2bn($productGroup->sum('total_qty')) }}</td>
                <td>{{ en2bn($productGroup->sum('total_amount')) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"> @lang('Grand Total')</th>

            @php
                $grandTotalQty = 0;
                $grandTotalAmount = 0;
            @endphp

            @foreach ($customers as $customer)
                @php
                    $totalQtyByCustomer = $products
                        ->flatMap(function ($productGroup) use ($customer) {
                            return $productGroup
                                ->where('customer_id', $customer->id)
                                ->pluck('total_qty');
                        })
                        ->sum();

                    $grandTotalQty += $totalQtyByCustomer;

                @endphp

                @if ($type == 'WC')
                    <th>{{ en2bn($totalQtyByCustomer) }}</th>
                @endif
            @endforeach

            @php
                $grandTotalAmount = $products
                    ->flatMap(function ($group) {
                        return $group->pluck('total_amount');
                    })
                    ->sum();
            @endphp

            <th>{{ en2bn(number_format($grandTotalQty, 0, '.', ',')) }}</th>
            <th>{{ en2bn(number_format($grandTotalAmount, 0, '.', ',')) }}</th>
        </tr>
    </tfoot>
</table>

<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('SL No')</th>
            <th>@lang('UID')</th>
            <th>@lang('Customer Name')</th>
            @foreach ($dates as $date)
                <th>{{ en2bn(date('d', strtotime($date))) }}</th>
            @endforeach
            <th>@lang('Total Order')</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandTotals = array_fill_keys($dates, 0);
            $allTotal = 0;
        @endphp

        @foreach ($customers as $customer)
            <tr>
                <td>{{ en2bn($loop->iteration) }}</td>
                <td>{{ en2bn($customer->uid) }}</td>
                <td style="text-align: left">{{ $customer->name }}</td>
                @php $rowTotal = 0; @endphp
                @foreach ($dates as $date)
                    @php
                        $count = $orderData[$customer->id][$date] ?? 0;
                        $rowTotal += $count;
                        $grandTotals[$date] += $count;
                        $allTotal += $count;
                    @endphp
                    <td>{{ en2bn($count) }}</td>
                @endforeach
                <td>{{ en2bn($rowTotal) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align:right">@lang('Total')</th>
            @foreach ($dates as $date)
                <th>{{ en2bn($grandTotals[$date]) }}</th>
            @endforeach
            <th>{{ en2bn($allTotal) }}</th>
        </tr>
    </tfoot>
</table>

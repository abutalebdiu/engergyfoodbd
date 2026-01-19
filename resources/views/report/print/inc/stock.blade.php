<tbody>
    @php
        $total_stock_value = 0;
       
        $total_purchase_item_value = 0;
    @endphp
     @foreach ($data['productswithdepartments'] as $departmentId => $products)
        @php
            $departmentName = optional($products->first()->department)->name;
        @endphp
        <tr>
            <td colspan="5" class="font-weight-bold text-primary text-start">
                {{ $departmentName ?: 'No Department' }}
            </td>
        </tr>
                                    
    
        @foreach ($products as $item)
            <tr>
                <td>{{ en2bn($loop->iteration) }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ en2bn($item->getstock($item->id)) }}</td>
                <td>{{ en2bn($item->sale_price) }}</td>
                <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($item->sale_price * $item->getstock($item->id), 2)) }}</td>
            </tr>
    
            @php
                $total_stock_value += $item->getstock($item->id);
              
                $total_purchase_item_value += $item->sale_price * $item->getstock($item->id);
            @endphp
        @endforeach
    @endforeach
    
    

    <tr>
        <td colspan="2">@lang('Total')</td>
        <td>{{ en2bn($total_stock_value) }}</td>
        <td></td>
        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($total_purchase_item_value, 2)) }}</td>
    </tr>
</tbody>

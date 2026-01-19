<tbody>
    @php
        $total_stock_value = 0;
        $total_purchase_item_value = 0;
    @endphp
    
    @foreach($data['itemswithcategories'] as $items)
      @php
            $categoryName = optional($items->first()->category)->name;
        @endphp
    
       <tr>
            <td colspan="5" class="font-weight-bold text-primary text-start">
                {{ $categoryName ?: 'No Category' }}
            </td> 
        </tr>
    @foreach ($items as $item)
        <tr>
            <td>{{ en2bn($loop->iteration) }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ en2bn($item->stock($item->id)) }}</td>
            <td>{{ en2bn($item->price) }}</td>
            <td style="text-align:right;padding-right:10px">{{ en2bn(number_format($item->price * $item->stock($item->id), 2)) }}</td>
        </tr>

        @php
            $total_stock_value += $item->stock($item->id);
            $total_purchase_item_value += $item->price * $item->stock($item->id);
        @endphp
    @endforeach
    @endforeach
    
    

    <tr>
        <td colspan="2">@lang('Total')</td>
        <td>{{ en2bn($total_stock_value) }}</td>
        <td></td>
        <td  style="text-align:right;padding-right:10px">{{ en2bn(number_format($total_purchase_item_value, 2)) }}</td>
    </tr>
</tbody>

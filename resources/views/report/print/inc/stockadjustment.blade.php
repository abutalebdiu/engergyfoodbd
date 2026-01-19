<tbody>
    @php
        $total_stock_value = 0;
    @endphp
    @foreach ($data['products'] as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->category->name }}</td>
        <td>{{ $item->brand->name }}</td>
        <td>{{ $item->getstock($item->id) }}</td>
    </tr>
    @php
        $total_stock_value += $item->getstock($item->id);
    @endphp
    @endforeach
    <tr>
        <td colspan="3"></td>
        <td>{{ $total_stock_value }}</td>
    </tr>
</tbody>

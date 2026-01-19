<table class="table table-bordered table-hover table-striped">
    @foreach ($all_products as $product)
    <tr>
        <td>{{ $product->name }}</td>
        <td>0</td>
    </tr>
    @endforeach
</table>

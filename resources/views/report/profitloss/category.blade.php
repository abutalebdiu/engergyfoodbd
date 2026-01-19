<table class="table table-bordered table-hover table-striped">
    @foreach ($all_categories as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>0</td>
    </tr>
    @endforeach
</table>

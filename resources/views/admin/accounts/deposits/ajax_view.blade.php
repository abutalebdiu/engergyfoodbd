<tbody>
    @forelse($deposits as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $item->note }}</td>
            <td>{{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
            <td>{{ optional($item->paymentmethod)->name }}</td>
            <td>{{ optional($item->account)->title }}</td>
            <td>{{ en2bn(number_format($item->amount, 2, '.', ',')) }}</td>
            <td>
                <span class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
            </td>
            <td>
                <div class="btn-group">
                    <button data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('admin.deposit.edit', $item->id) }}">
                                <i class="bi bi-pencil"></i> @lang('Edit')
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
        </tr>
    @endforelse
</tbody>
<tfoot>
    <tr>
        <th colspan="5">@lang('Total')</th>
        <th>{{ en2bn(number_format($deposits->sum('amount'), 2, '.', ',')) }}</th>
        <th colspan="2"></th>
    </tr>
</tfoot>
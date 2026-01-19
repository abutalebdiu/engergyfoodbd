<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>SL</th>
            <th>Product</th>
            <th>Price</th>
            <th>QTY</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($purchse->purchasedetail))
            @foreach ($purchse->purchasedetail as $odetail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($odetail->product)->name }}</td>
                    <td>{{ $odetail->price }}</td>
                    <td>{{ $odetail->qty }}</td>
                    <td>{{ number_format($odetail->amount) }}</td>
                    <td style="text-align: center !important">
                        <button class="btn btn-sm btn-outline-danger confirmationBtn"
                            data-question="@lang('Are you sure to remove this data from this list?')"
                            data-action="{{ route('admin.purchase.destroy', $odetail->id) }}">
                            <i class="fa fa-trash-alt"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    @if (!empty($purchse->purchasedetail))
        <tfoot>
            <tr class="bg-dark">
                <td></td>
                <td class="text-white">
                    Total
                </td>

                <td class="text-white">{{ number_format($purchse->purchasedetail->sum('price')) }}
                </td>
                <td class="text-white">{{ $purchse->purchasedetail?->sum('qty') }}</td>
                <td class="text-white">
                    {{ number_format($purchse->purchasedetail?->sum('amount'), 2) }}</td>
                <td></td>

            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Discount</th>
                <td>{{ number_format($purchse->discount, 2) }}</td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>AIT</th>
                <td>{{ number_format($purchse->ait, 2) }}</td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>VAT</th>
                <td>{{ number_format($purchse->vat, 2) }}</td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Transport Cost</th>
                <td>{{ number_format($purchse->transport_cost, 2) }}</td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Grand Total</th>
                <td>{{ number_format($purchse->totalamount, 2) }}</td>
                <th></th>
            </tr>
        </tfoot>
    @endif
</table>
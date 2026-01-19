<div class="customer-info">
    <table>
        <tbody>
            <tr>
                <th style="text-align: left;width:9%">ডিলার নাম:</th>
                <td style="text-align: left;width:1%">:</td>
                <td style="text-align: left;width:40%">{{ $customer->name }}</td>
                <th style="text-align: left;width:9%">@lang('Mobile')</th>
                <td style="text-align: left;width:1%">:</td>
                <td style="text-align: left;width:40%">{{ en2bn($customer->mobile) }}</td>
            </tr>
            <tr>
                <th style="text-align: left;width:9%">ঠীকানা:</th>
                <td style="text-align: left;width:1%">:</td>
                <td style="text-align: left;width:40%">{{ $customer->address }}</td>
                <th style="text-align: left;width:9%">তারিখ</th>
                <td style="text-align: left;width:1%">:</td>
                <td style="text-align: left;width:40%">{{ en2bn(date('d-m-Y')) }}</td>
            </tr>
        </tbody>
    </table>
</div>

@foreach ($products->chunk(30) as $productChunk)
    <div style="width: 33%">
        <table border="1" style="width: 100%">
            <thead>
                <tr class="border-bottom">
                    <th style="width: 10%">@lang('SL No')</th>
                    <th style="width: 70%">@lang('Product')</th>
                    <th>@lang('Quantity')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productChunk as $key => $product)
                    <tr>
                        <td>{{ en2bn($loop->iteration) }}</td>
                        <td style="text-align: left">{{ $product->name }}</td>
                        <td>
                            <input class="form-control" type="text" name="price[]"
                                value="{{ en2bn($product->productCommission?->price ?? 0) }}" required>
                        </td>
                        <td class="text-end">
                            <input class="form-control amount" type="text" name="amount[]"
                                value="{{ en2bn($product->productCommission?->amount ?? 0) }}" required>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
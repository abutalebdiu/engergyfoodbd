<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Customer Product Commission List')</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        table th {
            font-size: 14px;
        }

        table td {
            font-size: 13px;
        }

        section::after {
            content: "";
            display: table;
            clear: both;
        }

        .products {
            float: left;
            width: 49%;
            margin-left: 2px;
        }
    </style>
</head>

<body>
    <div>
        <div class="wrapper">


            @foreach ($customers as $customer)
                <div>
                    <div class="print-header" style="text-align: center;margin-bottom:15px">
                        <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                        <p style="margin: 0;padding:0">{{ $general->address }}</p>
                        <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}
                        </p>
                    </div>

                    <h5 style="text-align: center;margin: 0;padding:5px 0">@lang('Customer Product Commission List')</h5>

                    <div class="customer-info" style="margin-bottom: 5px">
                        <table>
                            <tbody>
                                <tr>
                                    <th style="text-align: left;width:9%">ডিলার নাম:</th>
                                    <td style="text-align: left;width:1%">:</td>
                                    <td style="text-align: left;width:40%">{{ $customer->name }}</td>
                                    <th style="text-align: left;width:9%">@lang('Mobile')</th>
                                    <td style="text-align: left;width:1%">:</td>
                                    <td style="text-align: left;width:40%">{{ en2bn($customer->mobile) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: left;width:9%">ঠীকানা: </th>
                                    <td style="text-align: left;width:1%">:</td>
                                    <td style="text-align: left;width:40%">{{ $customer->address }}</td>
                                    <th style="text-align: left;width:9%">তারিখ</th>
                                    <td style="text-align: left;width:1%">:</td>
                                    <td style="text-align: left;width:40%">
                                        {{ en2bn(Date('d-m-Y')) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @php
                        $id = $customer->id;
                        $products = App\Models\Product\Product::active()
                            ->with([
                                'productCommission' => function ($query) use ($id) {
                                    $query->where('user_id', $id);
                                },
                            ])
                            ->get();
                        $i = 1;
                    @endphp

                    @foreach ($products->chunk(45) as $products)
                        <div class="products">
                            <table border="1" style="width: 100%">
                                <thead>
                                    <tr class="border-bottom">
                                        <th style="width: 10%">@lang('SL No')</th>
                                        <th style="width: 55%">@lang('Product')</th>
                                        <th style="width: 15%">@lang('Weight')</th>
                                        <th style="width: 10%">@lang('Price')</th>
                                        <th style="width: 10%">@lang('Commission')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ en2bn($i++) }} </td>
                                            <td style="text-align: left;padding-left:5px">
                                                {{ $product->name }}
                                            </td>
                                            <td>
                                                {{ $product->weight }}
                                            </td>
                                            <td class="">
                                                {{ en2bn($product->productCommission?->price ?? 0) }}
                                            </td>
                                            <td>
                                                {{ en2bn($product->productCommission?->amount ?? 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>

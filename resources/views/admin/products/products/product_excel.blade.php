<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product List</title>
</head>

<body>
    <div>
        <table style="text-align:center">
            <tr>
                <td colspan="6">
                    <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <p style="margin: 0;padding:0">{{ $general->address }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
                </td>
            </tr>
            <tr>

            </tr>
            <tr>
                <td colspan="6">
                    <p style="text-align: center">পণ্যে মূল্য তালিকা</p>
                </td>
            </tr>
        </table>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>@lang('SL')</th>
                <th>@lang('Name')</th>
                <th>@lang('Weight')</th>
                <th>@lang('Sale/Dealar Price')</th>
                <th>@lang('Store/Shop Price')</th>
                <th>@lang('Retail Price')</th>

            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($productswithgroupes as $departmentId => $products)
                @php
                    $departmentName = optional($products->first()->department)->name;
                @endphp
                <tr>
                    <td colspan="6" class="font-weight-bold text-primary text-start">
                        {{ $departmentName ?: 'No Department' }}
                    </td>
                </tr>
                @foreach ($products as $item)
                    <tr>
                        <td> {{ en2bn($i++) }} </td>
                        <td style="text-align: left"> {{ $item->name }} </td>
                        <td> {{ $item->weight }} </td>
                        <td> {{ en2bn($item->sale_price) }}</td>
                        <td> {{ en2bn($item->shop_price) }}</td>
                        <td> {{ en2bn($item->retail_price) }}</td>
                    </tr>
                @endforeach
            @endforeach

        </tbody>
    </table>
</body>

</html>

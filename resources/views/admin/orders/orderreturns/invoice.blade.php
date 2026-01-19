<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order - {{ $order->oid }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        .wrapper {
            margin: 20pt 40pt;
        }

        .print-header h4 {
            text-align: center;
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="print-header">
            <h4>{{ $general->site_name }}</h4>
            <p><span>Buyer Name: {{ optional($order->buyer)->name }}</span> <span style="float: right">Order Date:
                    {{ Date('d-M-Y', strtotime($order->date)) }}</span></p>
        </div>

        <table border="1">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Code</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>label</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->purchasedetail as $odetail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $odetail->code }}</td>
                        <td>{{ $odetail->name }}</td>
                        <td>

                        </td>
                        <td>{{ $odetail->label }}</td>
                        <td>{{ $odetail->color }}</td>
                        <td>{{ $odetail->size }}</td>
                        <td>{{ $odetail->price }}</td>
                        <td>{{ $odetail->qty }}</td>
                        <td>{{ number_format($odetail->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        Total
                    </td>
                    <td>{{ $order->purchasedetail->sum('qty') }}</td>
                    <td>{{ number_format($order->purchasedetail->sum('amount'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

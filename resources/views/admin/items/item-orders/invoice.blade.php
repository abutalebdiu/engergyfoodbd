<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order - {{ $order->iid }}</title>
    <style>
         @font-face {
            font-family: 'solaimanlipi';
            src: url('fonts/SolaimanLipi.ttf');
            font-weight: normal;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
        }

        .wrapper {
            margin: 20pt 40pt;
        }

        .print-header h4 {
             
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .customer-info{
            margin-bottom: 20px;
        }
    </style>
</head>

<body style="font-family: 'solaimanlipi', sans-serif">
    <div class="wrapper">
        <div class="print-header" style="text-align: center;margin-bottom:15px">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>

        <div class="customer-info">
            <table border="1">
                <tbody>
                    <tr>
                        <th colspan="2">Order Invoice</th>
                    </tr>
                    <tr>
                        <td style="text-align: left;padding:5px 10px;width:50%">
                            <span style="border-bottom:1px solid #000;font-style:italic">To</span> <br>
                            <h4>{{ $order->supplier?->name }}</h4>
                            <p><small>{{  optional($order->supplier)->address }}</small></p>
                        </td>
                        <td style="text-align: right;padding:5px 10px">
                            <p>INVOICE# {{ $order->iid }}</p>
                            <p>DATE: {{ Date('d M Y',strtotime($order->date)) }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="product-detail">
            <table border="1">
                <thead>
                    <tr style="text-align: center">
                        <th>SL NO</th>
                        <th>ITEMS/Products</th>
                        <th>QTY</th>
                        <th>Unit Price</th>
                        <th>Total Price BDT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->itemOrderDetail as $odetail)
                        <tr style="text-align: center">
                            <td>0{{ $loop->iteration }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td>{{ $odetail->qty }}</td>
                            <td>Tk. {{ number_format($odetail->price,2) }}</td>
                            <td style="text-align: right;padding-right:30px">Tk. {{ number_format($odetail->total,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right">Invoice / Net payment </td>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->itemOrderDetail->sum('total'),2) }}</td>
                    </tr>

                    <tr>
                        <th colspan="4">Discount</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->discount,2) }}</td>
                    </tr>
                    

                    <tr>
                        <th colspan="4">Transport Cost</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->transport_cost,2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="4">Labour Cost</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->labour_cost,2) }}</td>
                    </tr>

                    <tr>
                        <th colspan="4">Grand Total</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->totalamount,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5">IN WORD: {{ numberToWord($order->totalamount) }} Taka Only</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</body>
</html>

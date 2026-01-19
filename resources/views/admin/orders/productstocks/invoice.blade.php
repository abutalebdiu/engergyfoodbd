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
            text-align: ;
            margin-bottom: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-info{
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="print-header">
            <h4>{{ $general->site_name }}</h4>             
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
                            <h4>{{ $order->customer?->name }}</h4>
                            <p><small>{{ $order->customer?->address }}</small></p>
                        </td>
                        <td style="text-align: right;padding:5px 10px"> 
                            <p>INVOICE# TTL-DGL- 24121</p>
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
                    @foreach ($order->orderdetail as $odetail)
                        <tr style="text-align: center">
                            <td>0{{ $loop->iteration }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td>{{ $odetail->qty }}</td>
                            <td>Tk. {{ number_format($odetail->price,2) }}</td>
                            <td style="text-align: right;padding-right:30px">Tk. {{ number_format($odetail->amount,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right">Invoice / Net payment(Excluding AIT & VAT)</td>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->orderdetail->sum('amount'),2) }}</td>
                    </tr>
                    
                    <tr>
                        <th colspan="4">Discount</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->discount,2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="4">VAT</th>
                        <td style="text-align: right;padding-right:30px">Tk. {{ number_format($order->vat,2) }}</td>
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
        <div class="payment-terms" style="margin-top:30px">
            <table border="1">
                <tbody>
                    <tr>
                        <th colspan="2" style="text-align: left">PAYMENT TERMS & CONDITIONS</th>
                    </tr>
                    <tr>
                        <td>Payment</td>
                        <td>Cash or A/C Payee Chaque in favor of “Tetra Technology Ltd.”</td>
                    </tr>
                    <tr>
                        <td>AIT & VAT</td>
                        <td>Buyer will pay AIT & VAT according the rules if necessary.</td>
                    </tr>
                    <tr>
                        <td>Delivery</td>
                        <td>After payment confirmation</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

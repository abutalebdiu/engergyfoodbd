<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Quotation Invoice') - {{ $quotation->qid }}</title>
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

        .customer-info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="print-header" style="text-align: center;margin-bottom:15px">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>
        <div class="customer-info">
            <table>
                <tbody>
                    <tr>
                        <th style="text-align: left;width:9%">আইডি:</th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ en2bn(optional($quotation->customer)->uid) }}</td>
                        <th style="text-align: left;width:9%">ইনভয়েস নং</th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ $quotation->qid }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;width:9%">ডিলার নাম:</th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ optional($quotation->customer)->name }}</td>
                        <th style="text-align: left;width:9%">@lang('Mobile')</th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ en2bn(optional($quotation->customer)->mobile) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;width:9%">ঠীকানা: </th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ optional($quotation->customer)->address }}</td>

                        <th style="text-align: left;width:9%">তারিখ</th>
                        <td style="text-align: left;width:1%">:</td>
                        <td style="text-align: left;width:40%">{{ en2bn(date('d-m-Y', strtotime($quotation->date))) }}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left">সেলসম্যান </th>
                        <td style="width: 1%">:</td>
                        <td style="text-align: left"></td>
                        <th style="text-align: left">ড্রাইভার</th>
                        <td style="width: 1%">:</td>
                        <th></th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="quotation-title">
            <p style="margin: 0;padding:0;text-align:center">
                @lang('Quotation Invoice')
            </p>
        </div>
        <div class="product-detail">
            <table border="1">
                <thead>
                    <tr style="text-align: center">
                        <th>ক্রমিক নং</th>
                        <th>নাম</th>
                        <th>@lang('Weight')</th>
                        <th>অর্ডার পিস</th>
                        <th>প্রতি পিছ টাকা</th>
                        <th>মোট টাকা</th>
                        <th>মোট নিট টাকা</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotation->quotationdetail as $odetail)
                        <tr style="text-align: center">
                            <td>{{ en2bn($loop->iteration) }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td style="text-align: center">{{ optional($odetail->product)->weight }}</td>
                            <td style="text-align: center">{{ en2bn($odetail->qty) }}</td>
                            <td style="text-align: center">{{ en2bn(number_format($odetail->price, 2, '.', ',')) }}
                            </td>
                            <td style="text-align: right;padding-right:30px">
                                {{ en2bn(number_format($odetail->amount, 2, '.', '.')) }}</td>
                            <td style="text-align: right;padding-right:30px">
                                {{ en2bn(number_format($odetail->amount, 2, '.', '.')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Sub Total')</th>
                        <td style="text-align: right;padding-right:30px">
                            {{ en2bn(number_format($quotation->sub_total, 2, '.', ',')) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Net Amount')</th>
                        <td style="text-align: right;padding-right:30px">
                            {{ en2bn(number_format($quotation->net_amount, 2, '.', ',')) }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Commission')</th>
                        <td style="text-align: right;padding-right:30px">
                            @if (optional($quotation->customer)->commission_type == 'Monthly')
                                মাসিক
                            @else
                                {{ en2bn(number_format($quotation->commission, 2, '.', ',')) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Grand Total')</th>
                        <td style="text-align: right;padding-right:30px">
                            {{ en2bn(number_format($quotation->grand_total, 2, '.', ',')) }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Previous Due')</th>
                        <td style="text-align: right;padding-right:30px">
                            {{ en2bn(number_format($quotation->previous_due, 2, '.', ',')) }}
                        </td>
                    </tr>

                    <tr>
                        <th colspan="5"></th>
                        <th style="text-align: left">@lang('Total Due Amount')</th>
                        <td style="text-align: right;padding-right:30px">
                            {{ en2bn(number_format($quotation->customer_due, 2, '.', ',')) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">কথায়: {{ $banglanumber }} টাকা মাত্র</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="signature-detail">
            <table style="width: 100%;text-align:center;margin-top:15%">
                <tr>
                    <td><span style="border-top:1px solid #000;">@lang('Delivery Incharge')</span></td>
                    <td><span style="border-top:1px solid #000;">@lang('Receiver Signature')</span></td>
                    <td><span style="border-top:1px solid #000;">@lang('Orders Receiver')</span></td>
                </tr>
            </table>
        </div>
        <div class="page-footer-notice" style="margin-top:10%;font-size:14px">
            <p>ক্যারেট প্রতি পিছ ........ টাকা। এই চালানের ক্যারেটের সংখ্যা ............ পিছ x ........ টাকা =
                ...............
                মোট
                টাকা </p>
            <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
        </div>
    </div>
</body>

</html>

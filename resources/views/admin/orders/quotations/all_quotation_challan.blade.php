<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @lang('Quotation Challan')</title>
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
            font-size: 14pt;
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
            font-size: 14px;
        }

        .customer-info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @foreach($orders as $quotation)
            <div class="singlewrapper" style="height:11.6in;width:100%">
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
                                <th style="text-align: left;width:9%">ডিলার নাম</th>
                                <td style="width: 1%">:</td>
                                <td style="text-align: left;width:40%">{{ optional($quotation->customer)->name }}</td>
                                <th style="text-align: left;width:9%">ডিলার নং</th>
                                <td style="text-align: left;width:1%">:</td>
                                <td style="text-align: left;width:40%"></td>
                            </tr>
                            <tr>
                                <th style="text-align: left">ঠীকানা</th>
                                <td style="width: 1%">:</td>
                                <td style="text-align: left">{{ optional($quotation->customer)->address }}</td>
                                <th style="text-align: left">গাড়ি নং</th>
                                <td style="width: 1%">:</td>
                                <td style="text-align: left"></td>
                            </tr>
        
                            <tr>
                                <th style="text-align: left">সেলসম্যান </th>
                                <td style="width: 1%">:</td>
                                <td style="text-align: left"></td>
                                <th>ড্রাইভার</th>
                                <td style="width: 1%">:</td>
                                <th></th>
                            </tr>
                            <tr>
                                <th style="text-align: left">তারিখ</th>
                                <td style="width: 1%">:</td>
                                <td style="text-align: left">{{ en2bn(Date('d-m-Y', strtotime($quotation->date))) }}</td>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="product-detail">
                    <h3 style="text-align:center;margin:0 0 5px 0;padding:0;"><span> @lang('Quotation') @lang('Challan')</span>
                    </h3>
                    <table border="1">
                        <thead>
                            <tr style="text-align: center">
                                <th style="width: 15%">ক্রমিক নং</th>
                                <th style="width: 70%">পণ্য</th>
                                <th style="width: 15%">পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotation->quotationdetail as $odetail)
                                <tr style="text-align: center">
                                    <td>0{{ en2bn($loop->iteration) }}</td>
                                    <td>{{ optional($odetail->product)->name }}</td>
                                    <td>{{ en2bn($odetail->qty) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
        
                                <td colspan="2">মোট</td>
                                <td> {{ en2bn($quotation->quotationdetail->sum('qty')) }}</td>
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
                    <p>ক্যারেট প্রতি পিছ ১২০ টাকা। এই চালানের ক্যারেটের সংখ্যা ............ পিছ x ১২০ টাকা = ...............
                        মোট টাকা </p>
                    <p style="text-align: center;margin-top:5%">বি:দ্র: বিক্রিত পণ্য ফেরত যোগ্য নয়</p>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>

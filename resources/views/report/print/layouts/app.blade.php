<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
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
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
            font-size: 10px;
            font-weight: 300;
            font-style: normal;
        }

        .wrapper {
            padding: 0px 5px;
            display: flex;
            flex-wrap: wrap;
        }


        .row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
        }


        [class*="col-"] {
            box-sizing: border-box;
            padding: 0 10px;
        }


        .col-1 {
            width: 8.33%;
        }

        .col-2 {
            width: 16.66%;
        }

        .col-3 {
            width: 25%;
        }

        .col-4 {
            width: 33.33%;
        }

        .col-5 {
            width: 41.66%;
        }

        .col-6 {
            width: 50%;
        }

        .col-7 {
            width: 58.33%;
        }

        .col-8 {
            width: 66.66%;
        }

        .col-9 {
            width: 75%;
        }

        .col-10 {
            width: 83.33%;
        }

        .col-11 {
            width: 91.66%;
        }

        .col-12 {
            width: 100%;
        }


        .row>[class*="col-"] {
            display: flex;
            flex-direction: column;
        }


        .card {
            border: none;
            border-radius: 0.375rem;
            background-color: #fff;
            overflow: hidden;
        }

        .card-body {
            padding: 20px 0px;
            background-color: #fff;
        }


        .card-title {
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }


        .card-text {
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #6c757d;
        }

        .print-header h4 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 0px;
        }

        .print-header p {
            text-align: center;
        }

        .logo {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            text-align: left;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table tr td .text-end {
            text-align: right;
        }


        .mt-10 {
            margin-top: 10px;
        }

        .footer {
            line-height: 2px;
        }

        h3 {
            text-align: center;
        }

        h5 {
            text-align: center;
        }

        h6 {
            text-align: center;
            font-size: 12px;
            font-weight: 300;
            line-height: 18px;
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>
</head>

<body style="font-family: 'solaimanlipi', sans-serif">
    <div class="wrapper">

        @include('report.print.layouts.print_header')

        @yield('content')

        <div class="mt-10 footer">
            <p>{{ $general->site_name }}</p>
            <p>Printed on {{ Date('d M Y') }}</p>
            <p>Developed By: <a href="https://softech.com.bd" target="_blank">SOFTECH BD</a></p>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product List</title>
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

<body>
    <div>
        <div class="print-header" style="text-align: center;margin-bottom:15px">
            <h4 style="margin: 0;padding:0;font-size:18pt">{{ $general->site_name }}</h4>
            <p style="margin: 0;padding:0">{{ $general->address }}</p>
            <p style="margin: 0;padding:0">অফিস: {{ $general->phone }}, হেল্প লাইন:{{ $general->mobile }}</p>
        </div>
        <p style="text-align: center">পণ্যে মূল্য তালিকা</p>
        <table border="1">
            <thead>
                <tr>
                    <th>@lang('SL')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Code No')</th>                    
                    <th>@lang('Sale Price')</th>
                    <th>@lang('Store/Shop Price')</th>
                    <th>@lang('Retail Price')</th>
                     
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $item)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td style="text-align: left"> {{ $item->name }} </td>
                        <td> {{ $item->code }} </td>                        
                        <td> {{ $item->sale_price }}</td>
                        <td> {{ $item->shop_price }}</td>
                        <td> {{ $item->retail_price }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

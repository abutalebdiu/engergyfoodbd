<div class="print-header">
    <h4>{{ $general->site_name }}</h4>
    <h6>{{ $general->address }}</h6>
    <h6>অফিসঃ {{ $general->phone }}, হেল্প লাইনঃ {{ $general->mobile }}</h6>
    <h6 style="font-weight: 600; text-decoration: underline; font-size: 14px;">@yield('title')</h6>
    <h6 style="text-align: right;">প্রিন্ট তারিখঃ {{ en2bn(now()) }}</h6>
</div>

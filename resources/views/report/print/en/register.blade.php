@extends('report.print.layouts.app')

@section('title')
Register Report
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Opening</th>
                <th>Closing</th>
                <th>Contact</th>
                <th>Total Purchases</th>
                <th>Total Purchases Return</th>
                <th>Total Sale</th>
                <th>Total Sale Return</th>
                <th>Opening Due Balance</th>
                <th>Due</th>
            </tr>
        </thead>
        @include('report.print.inc.register')
    </table>
</div>
@endsection



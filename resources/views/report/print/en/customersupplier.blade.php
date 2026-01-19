@extends('report.print.layouts.app')

@section('title')
Customer Supplier Report
@endsection
@section('content')
<div class="mt-10">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Contact</th>
                <th>Total Purcheses</th>
                <th>Total Purcheses Return</th>
                <th>Total Sale</th>
                <th>Total Sale Return</th>
                <th>Opening Due Balance</th>
                <th>Due</th>
            </tr>
        </thead>
        @include('report.print.inc.customersupplier')
    </table>
</div>
@endsection



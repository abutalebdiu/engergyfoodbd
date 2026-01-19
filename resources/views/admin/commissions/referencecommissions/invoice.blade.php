@extends('admin.layouts.app', ['title' => 'Commission Setup'])
@section('panel')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 text-capitalize">@lang('Commission Invoices')</a></h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="pb-3 col-12 col-md-12">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $key => $invoice)
                        <tr>
                            <td>
                                <label class="form-label text-capitalize">#{{ $invoice->invoice_id ?? $invoice->id }}</label>
                            </td>
                            <td>
                                <label class="form-label text-capitalize"> {{ $invoice->date }}</label>
                            </td>
                            <td>
                                <label class="form-label text-capitalize"> {{ $invoice->customer?->name }}</label>
                            </td>
                            <td>
                                <label class="form-label text-capitalize"> {{ $invoice->amount }}</label>
                            </td>
                            <td>
                                <label class="form-label text-capitalize"> {{ $invoice->payment_status }}</label>
                            </td>
                            <td>
                                <a href="{{ route('admin.referenceCommision.invoice.view', ['id' => $invoice->id]) }}" class="btn btn-outline-info float-start">@lang('View')</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('style')
    <style>
        .table tr td.size-50 {
            width: 50%;
        }
    </style>
@endpush

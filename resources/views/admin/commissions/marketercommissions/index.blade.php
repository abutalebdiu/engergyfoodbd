@extends('admin.layouts.app', ['title' => 'Marketer Commissions Setup'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Marketer Commissions')
                <a href="{{ route('admin.marketercommission.create') }}" class="btn btn-primary btn-sm float-end"><i
                        class="fa fa-plus"> </i> @lang('Add New')</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <select name="marketer_id" id="marketer_id" class="form-select select2 marketer_id">
                                    <option value="">@lang('Select Marketer')</option>
                                    @foreach ($marketers as $marketer)
                                        <option {{ request()->marketer_id == $marketer->id ? 'selected' : '' }}
                                            value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <select name="month_id" id="month_id" class="form-select">
                                    <option value=""> -- Select -- </option>
                                    @foreach ($months as $item)
                                        <option {{ request()->month_id == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <button type="submit" name="search" class="btn btn-primary btn-sm"><i
                                    class="bi bi-search"></i>
                                @lang('Search')</button>
                            <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i
                                    class="bi bi-download"></i>
                                @lang('PDF')</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Marketer')</th>
                            <th>@lang('Previous Due Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Due Payment')</th>
                            <th>@lang('Total Due Amount')</th>
                            <th>@lang('Payable Amount (Marketer Commssion)')</th>
                            <th>@lang('Marketer Commssion Paid')</th>
                            <th>@lang('Marketer Commssion Unpaid')</th>
                            <th>@lang('Overall Due')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry By')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $key => $invoice)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <span class="btn btn-primary btn-sm">Action</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.marketercommission.show', $invoice->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href="{{ route('admin.marketercommission.invoice.print', $invoice->id) }}">
                                                    <i class="las la-print"></i> @lang('Print')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.marketercommission.destroy', $invoice->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    {{ $invoice->invoice_no }}
                                </td>
                                <td>
                                    {{ Date('d-M-Y', strtotime($invoice->date)) }}
                                </td>
                                <td>
                                    {{ $invoice->marketer?->name }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->previous_due, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->net_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->paid_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->customer_due_payment, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->total_due_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->payable_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->marketercommissionpayment->sum('amount'), 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->payable_amount - $invoice->marketercommissionpayment->sum('amount'), 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->total_due_amount - $invoice->payable_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ $invoice->payment_status }}
                                </td>
                                <td>
                                    {!! entry_info($invoice) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

@extends('admin.layouts.app', ['title' => 'Commission Setup'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Commission Invoices')
                <a href="{{ route('admin.commissioninvoice.create') }}" class="btn btn-primary btn-sm float-end"><i
                        class="fa fa-plus"> </i> @lang('Add New')</a>
            </h6>
        </div>
        <div class="card-body">

            <form action="{{ route('admin.commissioninvoice.index') }}" method="get">
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <select name="customer_id" id="customer_id" class="form-select select2">
                                <option value="">@lang('Search Customer')</option>
                                @foreach ($customers as $customer)
                                    <option {{ request()->customer_id == $customer->id ? 'selected' : '' }}
                                        value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <button type="submit" name="search" class="btn btn-primary btn-sm"><i
                                    class="bi bi-search"></i>
                                @lang('Search')</button>
                            <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i
                                    class="bi bi-download"></i>
                                @lang('PDF')</button>
                            <button type="submit" name="invoice" class="btn btn-primary btn-sm"><i
                                    class="bi bi-download"></i>
                                @lang('All C.Invoice')</button>

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
                            <th>@lang('Month')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Last Month Due')</th>
                            <th>@lang('Order Amount')</th>
                            <th>@lang('Return Amount')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Paid Amount')</th>
                            <th>@lang('Customer Due Payment')</th>
                            <th>@lang('Commission Type')</th>
                            <th>@lang('Payable Commission')</th>
                            <th>@lang('Receivable Amount')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissioninvoices as $key => $invoice)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <span class="btn btn-primary btn-sm">Action</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.commissioninvoice.show', $invoice->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <a
                                                    href="{{ route('admin.commissioninvoice.invoice.print', $invoice->id) }}">
                                                    <i class="las la-print"></i> @lang('Print')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.commissioninvoice.destroy', $invoice->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    {{ $invoice->invoice_id ?? $invoice->id }}
                                </td>
                                <td>
                                    {{ optional($invoice->month)->name }} - {{ $invoice->year }}
                                </td>
                                <td style="text-align:left">
                                    {{ en2bn($invoice->customer?->uid) }} - {{ $invoice->customer?->name }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->last_month_due, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->order_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->return_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->net_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->commission, 2, '.', ',')) }}
                                </td>
                                <td>
                                    @if ($invoice->customer->commission_type == 'Daily')
                                        {{ en2bn(number_format($invoice->net_amount - $invoice->commission, 2, '.', ',')) }}
                                    @else
                                        {{ en2bn(number_format($invoice->net_amount, 2, '.', ',')) }}
                                    @endif
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->paid_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ en2bn(number_format($invoice->customer_due_payment, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ $invoice->customer?->commission_type }}
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->commission_amount, 2, '.', ',')) }}
                                </td>

                                <td>
                                    {{ en2bn(number_format($invoice->receivable_amount, 2, '.', ',')) }}
                                </td>
                                <td>
                                    {{ $invoice->payment_status }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">@lang('Total')</th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('last_month_due'), 2, '.', ',')) }}</th>
                            <th colspan="9"></th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('amount'), 2, '.', ',')) }}</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="14">@lang('Different')</th>
                            <th>{{ en2bn(number_format($commissioninvoices->sum('amount') - $commissioninvoices->sum('last_month_due'), 2, '.', ',')) }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
@include('components.select2')

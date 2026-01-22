@extends('admin.layouts.app', ['title' => 'Transaction History List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Transaction History List </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select select2">
                            <option value="">@lang('Search Customer')</option>
                            @foreach ($customers as $customer)
                                <option {{ request()->customer_id == $customer->id ? 'selected' : '' }}
                                    value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" id="searchBtn" class="btn btn-primary btn-sm mb-2"><i
                                class="bi bi-search"></i> @lang('Search')</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Transaction No')</th>
                            <th>@lang('Module')</th>
                            <th>@lang('Module Invoice No')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Mother Account')</th>
                            <th>@lang('Pre Balance')</th>
                            <th>@lang('Credit Amount')</th>
                            <th>@lang('Debit Amount')</th>
                            <th>@lang('Post Balance')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Client/Party')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactionhistories as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }}</td>
                                <td> {{ $item->txt_no }} </td>
                                <td> {{ optional($item->moduletype)->name }}</td>
                                <td> {{ $item->invoice_no }}</td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ number_format($item->pre_balance) }}</td>
                                <td>
                                    @if ($item->cdf_type == 'credit')
                                        {{ number_format($item->amount) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->cdf_type == 'debit')
                                        {{ number_format($item->amount) }}
                                    @endif
                                </td>
                                <td> {{ number_format($item->per_balance) }}</td>
                                <td> {{ $item->cdf_type }}</td>
                                <td> {{ $item->note }}</td>
                                <td>
                                    @if ($item->client_id)
                                        @if ($item->module_type == 1)
                                            {{ optional($item->client)->name }}
                                            ({{ optional($item->client)->company_name }})
                                        @elseif($item->module_id == 6 || $item->module_id == 14 || $item->module_id == 13 || $item->module_id == 12)
                                            {{ optional($item->employee)->name }}
                                        @elseif($item->module_id == 22)
                                            {{ optional($item->marketer)->name }}
                                        @else
                                            {{ optional($item->client)->name }}
                                            ({{ optional($item->client)->company_name }})
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    {!! entry_info($item) !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
                {{ $transactionhistories->links() }}
            </div>
        </div>
    </div>
@endsection
@include('components.select2')

@extends('admin.layouts.app', ['title' => 'Marketer Commission Payment History'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Marketer Commission Payment History
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary btn-sm float-end"> <i class="fa fa-list"></i>
                    @lang('Order List')</a>
            </h5>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <select name="marketer_id" id="marketer_id" class="form-select select2">
                            <option value="">@lang('Select marketer')</option>
                            @foreach ($marketers as $marketer)
                                <option
                                    @if (isset($marketer_id)) {{ $marketer_id == $marketer->id ? 'selected' : '' }} @endif
                                    value="{{ $marketer->id }}"> {{ $marketer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            Search</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            PDF</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            Excel</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Marketer')</th>
                            <th>@lang('Invoice No')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($marketerCommissionPayments as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ optional($item->marketer)->name }} </td>
                                <td> {{ optional($item->marketercommission)->invoice_no }} </td>
                                <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }} </td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ $item->date }}</td>
                                <td>{{ optional($item->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.marketercommissionpayment.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm confirmationBtn btn-outline--success"
                                                    data-action="{{ route('admin.marketercommissionpayment.destroy', $item->id) }}"
                                                    data-question="@lang('Are you sure to Delete this Product?')" type="button">
                                                    <i class="fa fa-trash"></i>@lang('Delete')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th>Total</th>
                            <th>{{ en2bn(number_format($marketerCommissionPayments->sum('amount'), 2, '.', ',')) }}</th>
                            <td colspan="6"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
            {{ $marketerCommissionPayments->links() }}
        </div>
    </div>
    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')

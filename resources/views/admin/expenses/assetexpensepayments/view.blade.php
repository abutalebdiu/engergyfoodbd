@extends('admin.layouts.app', ['title' => __('Asset Expense Payment List')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Asset Expense Payment List')
                <a href="{{ route('admin.assetexpensepayment.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add Asset Expense Payment')</a>
            </h5>
        </div>
        <div class="card-body">
            <form action="">
                <div class="mb-3 row">
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <select class="form-select" name="asset_expense_id" required>
                                <option value="">@lang('Select One')</option>
                                @foreach ($assetexpenses as $item)
                                    <option {{ request()->asset_expense_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Asset Expense')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Entry User')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assetexpensepayments as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.assetexpensepayment.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.assetexpensepayment.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td> {{ $item->assetexpense?->name }} </td>
                                <td> {{ $item->account?->title }} </td>
                                <td> {{ en2bn(number_format($item->amount, 2)) }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td> {{ optional($item->entryuser)->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="5">@lang('Total')</th>
                            <th>{{ en2bn(number_format($assetexpensepayments->sum('amount'), 2, '.', ',')) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

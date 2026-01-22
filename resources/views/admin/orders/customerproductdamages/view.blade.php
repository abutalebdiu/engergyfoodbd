@extends('admin.layouts.app', ['title' => 'Customers Products Damage List'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">
                @lang('Customers Products Damage List')

                <a href="{{ route('admin.customerproductdamage.create') }}" class="btn btn-outline-primary btn-sm float-end">
                    <i class="fa fa-plus"></i> @lang('Add New')</a>
            </h6>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <select name="customer_id" id="customer_id" class="form-select select2">
                            <option value="">@lang('Search Customer')</option>
                            @foreach ($customers as $customer)
                                <option
                                    @if (isset($customer_id)) {{ $customer_id == $customer->id ? 'selected' : '' }} @endif
                                    value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
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
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('Quantity')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Entry By')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($damages as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.customerproductdamage.show', $item->id) }}">
                                                    <i class="fa fa-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.customerproductdamage.edit', $item->id) }}">
                                                    <i class="fa fa-edit"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.customerproductdamage.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td class="text-start"> {{ optional($item->customer)->name }}</td>
                                <td> {{ en2bn($item->qty) }}</td>
                                <td> {{ en2bn(number_format($item->total_amount, 2, '.', ',')) }}</td>
                                <td> 
                                     {!! entry_info($item) !!}
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
                            <th colspan="4">@lang('Total')</th>
                            <th>{{ en2bn(number_format($damages->sum('qty'))) }} </th>
                            <th>{{ en2bn(number_format($damages->sum('total_amount'), 2)) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection
@include('components.select2')

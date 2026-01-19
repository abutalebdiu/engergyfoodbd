@extends('admin.layouts.app', ['title' => __('Deleted Quotation List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Deleted Quotation List')
            </h6>
        </div>
        <div class="card-body">
            <form action="" id="filterFormData">
                <div class="mb-3 row">
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

                    <div class="col-12 col-md-2">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @endif class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @endif class="form-control">
                    </div>

                    <div class="col-12 col-md-5">
                        <button type="submit" name="search" id="filterForm" class="btn btn-primary"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL No')</th>
                            <th>@lang('QID')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Customer')</th>
                            <th>@lang('qty')</th>
                            <th>@lang('Sub Total')</th>
                            <th>@lang('Net Amount')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Grand Total')</th>
                            <th>@lang('Due Amount')</th>
                            <th>@lang('Commission Status')</th>
                            <th>@lang('Previous Due')</th>
                            <th>@lang('Total Due Amount')</th>
                            <th>@lang('Deleted')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalqty=0; @endphp
                        @forelse($quotations as $item)
                            @php $totalqty +=$item->quotationdetail->sum('qty');  @endphp
                             <tr @if($item->order_id == null && \Carbon\Carbon::parse($item->date)->lt(\Carbon\Carbon::today())) style="background-color:#fbdddd" @endif>
                
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <span class="btn btn-primary btn-sm"> @lang('Action')</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.quotation.show', $item->id) }}">
                                                    <i class="fa fa-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            
                                            @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.deleted.list'))
                                            <li>
                                                <a href="{{ route('admin.quotation.restore', $item->id) }}"
                                                   class="dropdown-item text-success">
                                                    <i class="fa fa-undo"></i> @lang('Restore Quotation')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.quotation.forceDelete', $item->id) }}"  onclick="return confirm('@lang('Are you sure to permanently delete this quotation?')')"
                                                   class="dropdown-item text-danger">
                                                    <i class="fa fa-undo"></i> @lang('Permanently Remove')
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td><a href="{{ route('admin.quotation.show', $item->id) }}">
                                        {{ $item->qid }} </a> </td>
                                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }} </td>
                                <td class="text-start"> <a href="{{ route('admin.customers.statement', $item->customer_id) }}">
                                        {{ optional($item->customer)->name }}</a></td>
                                <td> {{ en2bn($item->quotationdetail->sum('qty')) }}</td>
                                <td> {{ en2bn(number_format($item->sub_total, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->net_amount, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->commission, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->grand_total, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->order_due, 2, '.', ',')) }}</td>
                                <td> <span  class="btn btn-{{ statusButton($item->commission_status) }} btn-sm">{{ $item->commission_status }}</span> </td>
                                <td> {{ en2bn(number_format($item->previous_due, 2, '.', ',')) }}</td>
                                <td> {{ en2bn(number_format($item->customer_due, 2, '.', ',')) }}</td>
                               
                                <td>
                                    {{ optional($item->deleteuser)->name ?? '-' }} <br>
                                    {{ $item->deleted_at }}
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
                            <th colspan="4"></th>
                            <th>@lang('Total')</th>
                            <th>
                                {{ en2bn($totalqty) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('sub_total'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('net_amount'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('commission'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('grand_total'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('order_due'), 2, '.', ',')) }}
                            </th>
                            <th>
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('previous_due'), 2, '.', ',')) }}
                            </th>
                            <th>
                                {{ en2bn(number_format($quotations->sum('customer_due'), 2, '.', ',')) }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {!! $quotations->links('pagination::bootstrap-5') !!}
            </div>

        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')


 


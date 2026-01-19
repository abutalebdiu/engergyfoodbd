@extends('admin.layouts.app', ['title' => 'Quotation Detail'])
@push('style')
    <style>
        table,
        td,
        th {
            padding: 2px !important;
        }
    </style>
@endpush
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">Quotation Detail

                <a href="{{ route('admin.quotation.invoice.print', $quotation->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Invoice Print</a>

                <a href="{{ route('admin.quotation.challan.print', $quotation->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Challan Print</a>

                <a href="{{ route('admin.quotation.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-list"></i> Quotation
                    List</a>

                    <a href="{{ route('admin.quotation.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-list"></i>  @lang('New Quotation')</a>

                <a href="{{ route('admin.order.pos.create') }}?quotation_id={{ $quotation->id }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-shopping-cart"></i> @lang('Make Order')
                </a>
                @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.edit'))
                    <a href="{{ route('admin.quotation.edit', $quotation->id) }}"
                        class="btn btn-outline-info btn-sm float-end ms-2">
                        <i class="fa fa-edit"></i> @lang('Edit')
                    </a>
                @endif

            </h6>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Order Info</h5>
                    <p>
                        <b>Quotation No</b> : {{ $quotation->qid }}, <br>
                        <b>@lang('Date')</b> : {{ en2bn(Date('d-m-Y', strtotime($quotation->date))) }}
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Customer Info</h5>
                    <p>
                        <b>@lang('ID')</b> : {{ optional($quotation->customer)->uid }}, <br>
                        <b>@lang('Name')</b> : {{ optional($quotation->customer)->name }}, <br>
                        <b>@lang('Mobile')</b> : {{ optional($quotation->customer)->mobile }}, <br>
                        <b>@lang('Address')</b> : {{ optional($quotation->customer)->address }} <br>
                        <b>@lang('Commission Type')</b> : {{ __(optional($quotation->customer)->commission_type) }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Product Detail')</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@lang('SL')</th>
                        <th>@lang('Product')</th>
                        <th>@lang('Weight')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Quantity')</th>
                        <th>@lang('Total')</th>
                        <th>@lang('Net Amount')</th>
                        <th>@lang('Commission')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotation->quotationdetail as $odetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align:left">{{ optional($odetail->product)->name }}</td>
                            <td>{{ optional($odetail->product)->weight }}</td>
                            <td>{{ en2bn($odetail->price) }}</td>
                            <td>{{ en2bn($odetail->qty) }}</td>
                            <td>{{ en2bn(number_format($odetail->amount, 2, '.', ',')) }}</td>
                            <td>{{ en2bn(number_format($odetail->amount, 2, '.', ',')) }}
                            </td>
                            <td>{{ en2bn(number_format($odetail->product_commission, 2, '.', ',')) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark">
                        <td></td>
                        <td></td>
                        <td class="text-white"> @lang('Total')</td>
                        <td class="text-white"> </td>
                        <td class="text-white">{{ en2bn($quotation->quotationdetail->sum('qty')) }}</td>
                        <td class="text-white">{{ en2bn(number_format($quotation->quotationdetail->sum('amount'), 2, '.', ',')) }}
                        </td>
                        <td class="text-white">{{ en2bn(number_format($quotation->sub_total, 2, '.', ',')) }}</td>
                        <td class="text-white">
                            {{ en2bn(number_format($quotation->quotationdetail->sum('product_commission'), 2, '.', ',')) }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Sub Total')</th>
                        <td> {{ en2bn(number_format($quotation->sub_total, 2, '.', ',')) }}</td>
                        <td></td>
                    </tr>

                    <tr>
                        <th colspan="5"></th>
                       <th>@lang('Net Amount')</th>
                       <td>{{ en2bn(number_format($quotation->net_amount, 2, '.', ',')) }}</td>
                       <td></td>
                   </tr>
                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Commission')</th>
                        <td>{{ en2bn(number_format($quotation->commission, 2, '.', ',')) }}</td>
                        <td>
                            <span
                                class="btn btn-{{ statusButton($quotation->commission_status) }} btn-sm">{{ $quotation->commission_status }}</span>
                        </td>
                    </tr>
                    <tr>
                         <th colspan="5"></th>
                        <th>@lang('Grand Total')</th>
                        <td>{{ en2bn(number_format($quotation->grand_total, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                         <th colspan="5"></th>
                        <th>@lang('Previous Due')</th>
                        <td> {{ en2bn(number_format($quotation->previous_due, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th colspan="5"></th>
                        <th>@lang('Total Due Amount')</th>
                        <td> {{ en2bn(number_format($quotation->customer_due, 2, '.', ',')) }}
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="8">@lang('IN WORD') : {{ $banglanumber }}
                            @lang('Taka Only')</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>



    <x-destroy-confirmation-modal />
@endsection

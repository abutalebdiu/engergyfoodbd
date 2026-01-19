@extends('admin.layouts.app', ['title' => 'Purchese list'])
@section('panel')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h6 class="mb-0 text-capitalize">
            Purchase List
        </h6>

        <div>
            <a href="{{ route('admin.purchase.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                    class="fa fa-plus"></i> New Purchase</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@lang('SL')</th>
                        <th>@lang('PID')</th>
                        <th>@lang('R. Invoice No')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Supplier')</th>
                        <th>@lang('Total QTY')</th>
                        <th>@lang('Sub Total')</th>
                        <th>@lang('Discount')</th>
                        <th>@lang('AIT')</th>
                        <th>@lang('VAT')</th>
                        <th>@lang('Transport Cost')</th>
                        <th>@lang('Total Amount')</th>
                        <th>@lang('Entry By')</th>
                        <th>@lang('Payment Status')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $item)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $item->pid }} </td>
                        <td> {{ $item->reference_invoice_no }} </td>
                        <td> {{ $item->date }} </td>
                        <td> {{ optional($item->supplier)->name }}</td>
                        <td> {{ $item->purchasedetail->sum('qty') }}</td>
                        <td> {{ number_format($item->sub_total) }}</td>
                        <td> {{ number_format($item->discount) }}</td>
                        <td> {{ number_format($item->ait) }}</td>
                        <td> {{ number_format($item->vat) }}</td>
                        <td> {{ number_format($item->transport_cost) }}</td>
                        <td> {{ number_format($item->totalamount) }}</td>
                        <td> {{ optional($item->entryuser)->name }}</td>
                        <td>
                            <span
                                class="btn btn-{{ statusButton($item->payment_status) }} btn-sm">{{ $item->payment_status }}</span>
                        </td>
                        <td>
                            <span class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="{{ route('admin.purchase.edit', $item->id) }}">
                                            <i class="fa fa-edit"></i> @lang('Edit')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.purchase.show', $item->id) }}">
                                            <i class="fa fa-eye"></i> @lang('Show')
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.purchasereturn.create') }}?purchase_id={{$item->id}}">
                                            <i class="fa fa-undo" aria-hidden="true"></i> @lang('Return')
                                        </a>
                                    </li>
                                    <li>
                                        <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                            data-question="@lang('Are you sure to remove this data from this list?')"
                                            data-action="{{ route('admin.purchase.destroy', $item->id) }}">
                                            <i class="fa fa-trash-alt"></i> @lang('Remove')
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
            </table><!-- table end -->
        </div>
    </div>
</div>


<x-destroy-confirmation-modal />
@endsection

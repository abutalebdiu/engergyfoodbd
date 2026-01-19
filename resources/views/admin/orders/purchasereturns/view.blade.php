@extends('admin.layouts.app', ['title' => 'Purchese Return list'])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                Purchase Return List
            </h6>
            <div></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>
                                @lang('Purchase ID')
                                </th>
                                <th>@lang('Supplier')</th>
                                <th>@lang('QTY')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Entry By')</th>
                                <th>@lang('Payment Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchasereturns as $item)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ optional($item->purchase)->pid }}</td>
                                    <td> {{ optional($item->supplier)->name }}</td>
                                    <td> {{ $item->purchasereturndetail->sum('qty') }}</td>
                                    <td> {{ number_format($item->amount) }}</td>
                                    <td> {{ $item->created_at->format('d-m-Y') }} </td>
                                    <td> {{ optional($item->entryuser)->name }}</td>
                                    <td><span
                                            class="btn btn-{{ statusButton($item->payment_status) }} btn-sm">{{ $item->payment_status }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">                                                
                                                <li>
                                                    <a href="{{ route('admin.purchasereturn.show', $item->id) }}">
                                                        <i class="fa fa-eye"></i> @lang('Show')
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                        data-question="@lang('Are you sure to remove this data from this list?')"
                                                        data-action="{{ route('admin.purchasereturn.destroy', $item->id) }}">
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

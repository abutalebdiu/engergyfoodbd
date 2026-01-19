@extends('admin.layouts.app', ['title' => 'Item Detail'])
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
            <h6 class="mb-0 text-capitalize">Item Details

                <a href="{{ route('admin.items.itemOrder.invoice.print', $itemorder->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Invoice Print</a>

                <a href="{{ route('admin.items.itemOrder.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-list"></i> Order
                    List</a>

                <a href="{{ route('admin.items.itemOrder.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                    <i class="fa fa-list"></i> Add New
                </a>

                <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                    data-bs-target="#order_payment">
                    <i class="fa fa-money-bill"></i> Make Payment
                </a>
            </h6>

            <!-- Modal -->
            <div class="modal fade" id="order_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.itemorderpayment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Item Make Payment
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Supplier Name:
                                                {{ $itemorder->supplier?->name }}</label>
                                            <input type="hidden" name="supplier_id" value="{{ $itemorder->supplier_id }}">
                                            <input type="hidden" name="item_order_id" value="{{ $itemorder->id }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Payment
                                                Method</label>
                                            <select name="payment_method_id" id="payment_method_id"
                                                class="form-control payment_method_id" required>
                                                <option value="">Select Method</option>
                                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                                    <option data-accounts="{{ $method->accounts }}"
                                                        @selected(old('payment_method_id' == @$method->id)) value="{{ $method->id }}">
                                                        {{ $method->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label class="form-label text-start">@lang('Account')</label>
                                            <select name="account_id" id="account_id" class="form-control account_id"
                                                required>
                                                <option value="">@lang('Select Account')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Total Amount')</label>
                                            <input type="text" value="{{ number_format($itemorder->due_balance) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Paid Amount')</label>
                                            <input type="text"
                                                value="{{ number_format($itemorder->paidamount($itemorder->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Unpaid Amount')</label>
                                            <input type="text"
                                                value="{{ number_format($itemorder->due_balance - $itemorder->paidamount($itemorder->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Pay Amount')</label>
                                            <input type="text" value="" name="amount" class="form-control"
                                                @if ($itemorder->due_balance == $itemorder->paidamount($itemorder->id)) disabled @endif>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Date')</label>
                                            <input type="date" value="{{ old('date') ? old('date') : Date('Y-m-d') }}"
                                                name="date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary float-end"
                                    onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                    value="@lang('Submit')">
                            </div>
                        </div>
                    </form>

                    @push('script')
                        <script>
                            $('[name=payment_method_id]').on('change', function() {
                                var accounts = $(this).find('option:selected').data('accounts');
                                var option = '<option value="">Select Account</option>';
                                $.each(accounts, function(index, value) {
                                    var name = value.title;
                                    option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                                        name + "</option>";
                                });

                                $('select[name=account_id]').html(option);
                            }).change();
                        </script>
                    @endpush

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Order Info</h5>
                    <p>
                        Order No : {{ $itemorder->pid }}, <br>
                        Date : {{ $itemorder->date }}, <br>
                        Total Amount : {{ $itemorder->totalamount }} <br>
                        Paid Amount : {{ $itemorder->paidamount($itemorder->id) }} <br>
                        Payable Amount : {{ $itemorder->due_balance - $itemorder->paidamount($itemorder->id) }} <br>
                        Payment Status : <span
                            class="btn btn-{{ statusButton($itemorder->payment_status) }} btn-sm">{{ $itemorder->payment_status }}</span>

                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Supplier Info</h5>
                    <p>
                        Name : {{ optional($itemorder->supplier)->name }}, <br>
                        Company : {{ optional($itemorder->supplier)->company_name }}, <br>
                        Mobile : {{ optional($itemorder->supplier)->mobile }}, <br>
                        Address : {{ optional($itemorder->supplier)->email }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Product Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@lang('SL')</th>
                        <th>@lang('Item')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('qty')</th>
                        <th>@lang('Amount')</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($itemorder->itemOrderDetail as $odetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td>{{ $odetail->price }}</td>
                            <td>{{ $odetail->qty }}</td>
                            <td>{{ number_format($odetail->total) }}</td>
                        </tr>

                        @php
                            $total += $odetail->total;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark">
                        <td></td>
                        <td class="text-white">
                            Total
                        </td>
                        <td class="text-white">{{ number_format($itemorder->itemOrderDetail->sum('price')) }}</td>
                        <td class="text-white">{{ $itemorder->itemOrderDetail->sum('qty') }}</td>
                        <td class="text-white">{{ number_format($itemorder->itemOrderDetail->sum('total'), 2) }}</td>

                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Discount</th>
                        <td>{{ number_format($itemorder->discount, 2) }}</td>

                    </tr>

                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Transport Cost</th>
                        <td>{{ number_format($itemorder->transport_cost, 2) }}</td>

                    </tr>

                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Labour Cost</th>
                        <td>{{ number_format($itemorder->labour_cost, 2) }}</td>

                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Grand Total</th>
                        <td>{{ number_format($itemorder->totalamount, 2) }}</td>

                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Previous Due</th>
                        <td>{{ number_format($itemorder->previous_due, 2) }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Paid Amount</th>
                        <td>{{ number_format($itemorder->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Due Amount</th>
                        <td>{{ number_format($itemorder->supplier_total_payable, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Item Order Payment List </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('No')</th>
                            <th>@lang('Supplier Name')</th>
                            <th>@lang('Item No')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Mother Account')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Status')</th>
                            {{-- <th>@lang('Action')</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($itempayments as $data)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $data->tnx_no }} </td>
                                <td> {{ optional($data->supplier)->name }} </td>
                                <td> {{ optional($data->item)->iid }} </td>
                                <td> {{ number_format($data->amount, 2) }}</td>
                                <td> {{ optional($data->paymentmethod)->name }}</td>
                                <td> {{ optional($data->account)->title }}</td>
                                <td> {{ $data->note }}</td>
                                <td> {{ $data->date }}</td>
                                <td>{{ optional($data->entryuser)->name }}</td>
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($data->status) }} btn-sm">{{ $data->status }}</span>
                                </td>
                                {{-- <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.itemorderpayment.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"></th>
                            <th>@lang('Total')</th>
                            <th>{{ number_format($itempayments->sum('amount'), 2) }}</th>
                            <td colspan="8"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
    @push('script')
    @endpush
@endsection

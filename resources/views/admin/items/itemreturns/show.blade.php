@extends('admin.layouts.app', ['title' => 'Item Order Return Detail'])
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
            <h6 class="mb-0 text-capitalize">Item Order Return Detail

                <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                    data-bs-target="#order_payment">
                    <i class="fa fa-money-bill"></i>  Payment
                </a>
            </h6>

            <!-- Modal -->
            <div class="modal fade" id="order_payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.itemreturnpayment.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Item Order Return Make Payment
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Customer Name:
                                                {{ $orderreturn->customer?->name }}</label>
                                            <input type="hidden" name="customer_id"
                                                value="{{ $orderreturn->customer_id }}">
                                            <input type="hidden" name="order_return_id" value="{{ $orderreturn->id }}">
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
                                            <label for="" class="form-label">Total Amount</label>
                                            <input type="text" value="{{ number_format($orderreturn->totalamount) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Paid Amount</label>
                                            <input type="text"
                                                value="{{ number_format($orderreturn->paidamount($orderreturn->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Unpaid
                                                Amount</label>
                                            <input type="text"
                                                value="{{ number_format($orderreturn->totalamount - $orderreturn->paidamount($orderreturn->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Pay
                                                Amount</label>
                                            <input type="text" value="" name="amount" class="form-control"
                                                @if ($orderreturn->totalamount == $orderreturn->paidamount($orderreturn->id)) disabled @endif>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Payment Date</label>
                                            <input type="date" value="{{ old('date') ? old('date') : Date('Y-m-d') }}"
                                                name="date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"
                                    @if ($orderreturn->totalamount == $orderreturn->paidamount($orderreturn->id)) disabled @endif>Save changes</button>
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
                    <h5 class="border-bottom">Item Order Info</h5>
                    <p>
                        Item Order No : {{ $orderreturn->order?->iid }}, <br>
                        Date : {{ $orderreturn->order?->date }}, <br>
                        Total Amount : {{ $orderreturn->totalamount }} <br>
                        Payment Status : <span
                            class="btn btn-{{ statusButton($orderreturn->payment_status) }} btn-sm">{{ $orderreturn->payment_status }}</span>

                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Customer Info</h5>
                    <p>
                        Name : {{ optional($orderreturn->customer)->name }}, <br>
                        Mobile : {{ optional($orderreturn->customer)->mobile }}, <br>
                        Company : {{ optional($orderreturn->customer)->company_name }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Product Return Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>QTY</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderreturn->orderreturndetail as $odetail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($odetail->product)->name }}</td>
                            <td>{{ $odetail->price }}</td>
                            <td>{{ $odetail->qty }}</td>
                            <td>{{ number_format($odetail->amount) }}</td>

                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-dark">
                        <td></td>
                        <td class="text-white">
                            Total
                        </td>
                        <td class="text-white">
                        </td>
                        <td class="text-white">{{ $orderreturn->orderreturndetail->sum('qty') }}</td>
                        <td class="text-white">
                            {{ number_format($orderreturn->orderreturndetail->sum('amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection

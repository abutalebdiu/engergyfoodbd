@extends('admin.layouts.app', ['title' => 'Order Detail'])
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
            <h6 class="mb-0 text-capitalize">Order Detail

                <a href="{{ route('admin.order.invoice.print', $order->id) }}"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-print"></i> Invoice Print</a>

                <a href="{{ route('admin.order.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-list"></i> Order
                    List</a>

                <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                    data-bs-target="#order_payment">
                    <i class="fa fa-money-bill"></i> Receive Payment
                </a>
            </h6>



            <!-- Modal -->
            <div class="modal fade" id="order_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.orderpayment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Sales Order Payment Receive
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Customer:
                                                {{ $order->customer?->name }}</label>
                                            <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
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
                                            <input type="text" value="{{ number_format($order->totalamount) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Paid Amount</label>
                                            <input type="text"
                                                value="{{ number_format($order->paidamount($order->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Unpaid
                                                Amount</label>
                                            <input type="text"
                                                value="{{ number_format($order->totalamount - $order->paidamount($order->id)) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Pay
                                                Amount</label>
                                            <input type="text" value="" name="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
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
                            Order No : {{ $order->oid }}, <br>
                            Date : {{ $order->date }}, <br>
                            Media : {{ $order->media }}, <br>
                            Total Amount : {{ $order->totalamount }} <br>
                            Payment Status : <span
                                class="btn btn-{{ statusButton($order->payment_status) }} btn-sm">{{ $order->payment_status }}</span>

                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <h5 class="border-bottom">Customer Info</h5>
                        <p>
                            Customer Name : {{ optional($order->customer)->name }}, <br>
                            Customer Mobile : {{ optional($order->customer)->mobile }}, <br>
                            Customer Email : {{ optional($order->customer)->email }}
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
                            <th>SL</th>
                            <th>Product</th>
                            <th>Purchase Price</th>
                            <th>Purchase Total</th>
                            <th>Price</th>
                            <th>QTY</th>
                            <th>Amount</th>
                            <th>Profit</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderdetail as $odetail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($odetail->product)->name }}</td>
                                <td>{{ $odetail->purchase_price }}</td>
                                <td>{{ $odetail->purchase_price * $odetail->qty }}</td>
                                <td>{{ $odetail->price }}</td>
                                <td>{{ $odetail->qty }}</td>
                                <td>{{ number_format($odetail->amount) }}</td>
                                <td>{{ $odetail->amount - $odetail->purchase_price * $odetail->qty }}</td>

                                <td style="text-align: center !important">
                                    <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                        data-question="@lang('Are you sure to remove this data from this list?')"
                                        data-action="{{ route('admin.orderdetail.destroy', $odetail->id) }}">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-dark">
                            <td></td>
                            <td colspan="2" class="text-white">
                                Total
                            </td>
                            <td class="text-white"> </td>
                            
                            <td></td>
                            <td class="text-white">{{ $order->orderdetail->sum('qty') }}</td>
                            <td class="text-white">{{ number_format($order->orderdetail->sum('amount')) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Discount</th>                     
                            <td>{{ number_format($order->discount) }}</td>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>VAT</th>
                            <td>{{ $order->vat }}</td>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Grand Total</th>
                            <td>{{ number_format($order->totalamount) }}</td>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>



        <x-destroy-confirmation-modal />
    @endsection

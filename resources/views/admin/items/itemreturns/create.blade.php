@extends('admin.layouts.app', ['title' => 'Add New Order Return'])
@section('panel')
    <form action="{{ route('admin.itemreturn.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Order Return - {{ $order->oid }}<a
                        href="{{ route('admin.itemreturn.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                            class="fa fa-list"></i> Order Return
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="{{ $order->customer?->name }}"
                            readonly>
                        <input type="hidden" name="order_id"  value="{{ $order->id }}">
                        <input type="hidden" name="customer_id"  value="{{ $order->supplier_id }}">
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference Name')</label>
                            <input class="form-control" type="text" name=""
                                value="{{ optional($order->reference)->name }}" readonly>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Order Date')</label>
                            <input class="form-control" type="text" name="date" value="{{ $order->date }}"
                                readonly>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table mt-2 table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th style="width: 100px">Return QTY</th>
                                        <th style="width: 100px">Return Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                        $total = 0;
                                    @endphp

                                    @foreach ($order->itemOrderDetail as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td>{{ $detail->price }}</td>
                                            <td>{{ $detail->qty - $detail->orderreturndetail->sum('qty') }}</td>
                                            <td>{{ $detail->price * ( $detail->qty - $detail->orderreturndetail->sum('qty')) }}</td>

                                            <td>
                                                <input type="hidden" name="order_detail_id[]"   value="{{ $detail->id }}">
                                                <input type="hidden" name="purchase_price[]"   value="{{ $detail->purchase_price }}">
                                                <input type="hidden" name="product_id[]"
                                                    value="{{ $detail->item_id }}">
                                                <input type="hidden" name="price[]" value="{{ $detail->price }}"
                                                    class="price">
                                                <input type="text" name="qty[]" class="form-control qty">
                                            </td>
                                            <td>
                                                <input type="text" name="amount[]" class="form-control amount" readonly>
                                            </td>
                                        </tr>

                                        @php
                                            $total += $detail->price * ( $detail->qty - $detail->orderreturndetail->sum('qty'));
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>



                        <div class="my-2 row">
                            <div class="col-12 col-md-3 offset-md-9">
                                <div class="form-group">
                                    <label for="">Net Total</label>
                                    <input type="text" class="form-control net_total" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="my-2 row">
                            <div class="col-12 col-md-3 offset-md-9">
                                <div class="form-group">
                                    <label for="">Discount</label>
                                    <input type="text" name="discount" class="form-control discount">
                                </div>
                            </div>
                        </div>

                        <div class="my-2 row fixedvat">
                            <div class="col-12 col-md-3 offset-md-9">
                                <div class="form-group">
                                    <label for="">Vat</label>
                                    <input type="text" name="vat" class="form-control vat">
                                </div>
                            </div>
                        </div>

                        <div class="my-2 row fixedait">
                            <div class="col-12 col-md-3 offset-md-9">
                                <div class="form-group">
                                    <label for="">Ait</label>
                                    <input type="text" name="ait" class="form-control ait">
                                </div>
                            </div>
                        </div>

                        <div class="my-2 row">
                            <div class="col-12 col-md-3 offset-md-9">
                                <div class="form-group">
                                    <label for="">Total</label>
                                    <input type="text" name="total_amount" class="form-control total_amount" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('script')
    <script>
        $(document).ready(function() {

            $(".fixedvat").hide();
            $(".fixedait").hide();
            // Function to update the amount field based on the quantity and price
            function updateAmounts() {
                let totalAmount = 0;

                $('tr').each(function() {
                    let $row = $(this);
                    let price = parseFloat($row.find('.price').val()) || 0;
                    let qty = parseFloat($row.find('.qty').val()) || 0;
                    let amount = price * qty;

                    $row.find('.amount').val(amount.toFixed(2));
                    totalAmount += amount;
                });

                $('.total_amount').val(totalAmount.toFixed(2));

                $('.net_total').val(totalAmount.toFixed(2));
            }

            function vatCalculation() {
                let net_total = parseFloat($('.net_total').val()) || 0;
                let vatType = "{{ @$order->vat_type }}";
                let vatValue = parseFloat("{{ @$order->vat }}") || 0;

                if (vatType == "Fixed") {
                    $('.fixedvat').show();
                }else{
                    let vat = net_total * vatValue / 100;
                    let total = parseFloat($('.total_amount').val());
                    total += vat;
                    $('.total_amount').val(total.toFixed(2));
                }

            }

            function aitCalculation() {
                let net_total = parseFloat($('.net_total').val()) || 0;
                let aitType = "{{ @$order->ait_type }}";
                let aitValue = parseFloat("{{ @$order->ait }}") || 0;
                if (aitType == "Fixed") {
                    $('.fixedait').show();
                }else{
                    let ait = net_total * aitValue / 100;
                    let total = parseFloat($('.total_amount').val());
                    total += ait;
                    $('.total_amount').val(total.toFixed(2));
                }
            }

            // key press to discount

            $('input[name="discount"]').on('keyup', function() {
                let discount = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total -= discount;
                $('.total_amount').val(total.toFixed(2));
            });

            // key down to discount

            $('input[name="discount"]').on('keydown', function() {
                let discount = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total += discount;
                $('.total_amount').val(total.toFixed(2));
            });

            $('input[name="vat"]').on('keyup', function() {
                let vat = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total += vat;
                $('.total_amount').val(total.toFixed(2));
            });

            $('input[name="vat"]').on('keydown', function() {
                let vat = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total -= vat;
                $('.total_amount').val(total.toFixed(2));
            });

            $('input[name="ait"]').on('keyup', function() {
                let ait = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total += ait;
                $('.total_amount').val(total.toFixed(2));
            });

            $('input[name="ait"]').on('keydown', function() {
                let ait = parseFloat($(this).val()) || 0;
                let total = parseFloat($('.total_amount').val()) || 0;
                total -= ait;
                $('.total_amount').val(total.toFixed(2));
            });

            // Event handler for quantity input change
            $('table').on('input', '.qty', function() {
                updateAmounts();
                vatCalculation();
                aitCalculation();
            });

            // Initial call to update amounts in case there are pre-filled values
            updateAmounts();

        });
    </script>
@endpush

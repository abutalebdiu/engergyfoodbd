@extends('admin.layouts.app', ['title' => 'Add New Purchase Return'])
@section('panel')
    <form action="{{ route('admin.purchasereturn.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Purchase Return - {{ $purchase->pid }}<a
                        href="{{ route('admin.purchasereturn.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                            class="fa fa-list"></i> Purchase Return
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" value="{{ $purchase->supplier->name }}"
                            readonly>
                        <input type="hidden" name="purchase_id"  value="{{ $purchase->id }}">
                        <input type="hidden" name="supplier_id"  value="{{ $purchase->supplier_id }}">
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference Invoice No')</label>
                            <input class="form-control" type="text" name="reference_invoice_no"
                                value="{{ $purchase->reference_invoice_no }}" readonly>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Purchase Date')</label>
                            <input class="form-control" type="text" name="date" value="{{ $purchase->date }}"
                                readonly>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered mt-2">
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
                                    @foreach ($purchase->purchasedetail as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detail->product->name }}</td>
                                            <td>{{ $detail->price }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ $detail->amount }}</td>
                                            <td>
                                                <input type="hidden" name="purchase_detail_id[]"
                                                    value="{{ $detail->id }}">
                                                <input type="hidden" name="product_id[]"
                                                    value="{{ $detail->product_id }}">
                                                <input type="hidden" name="price[]" value="{{ $detail->price }}"
                                                    class="price">
                                                <input type="text" name="qty[]" class="form-control qty" required>
                                            </td>
                                            <td>
                                                <input type="text" name="amount[]" class="form-control amount" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <div class="row">
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
                        <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline-info float-start">Back</a>
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
            }

            // Event handler for quantity input change
            $('table').on('input', '.qty', function() {
                updateAmounts();
            });

            // Initial call to update amounts in case there are pre-filled values
            updateAmounts();
        });
    </script>
@endpush

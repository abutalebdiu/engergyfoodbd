@extends('admin.layouts.app', ['title' => 'Edit Sale Order'])
@section('panel')
    <form action="{{ route('admin.order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Edit Sales Order - {{ $order->oid }}
                    <a href="{{ route('admin.order.index') }}" class="btn btn-outline-primary btn-sm float-end">
                        <i class="fa fa-list"></i> Order List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select select2" id="searchCustomer">
                            <option selected value="{{ $order->customer->id }}">{{ $order->customer->name }}</option>
                        </select>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Order ID')</label>
                            <input class="form-control" type="text" name="oid" value="{{ $order->oid ? $order->oid : '' }}" required>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Order Date')</label>
                            <input class="form-control" type="date" name="date" value="{{ $order->date ? $order->date : date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
                <div class="mt-2 mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">Search Product</label>
                            <div class="input-group">
                                <select class="form-select select2" id="search">
                                    <option value="">Search Product name or code</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Purchase Price</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyappend">
                                    @foreach ($order->orderdetail as $odetail)
                                    <tr>
                                        <td>{{ $odetail->product?->id }}</td>
                                        <td>{{ $odetail->product?->name }}</td>
                                        <td>
                                            <input type="hidden" name="order_detial_id[]" value="{{ $odetail->id }}">
                                            <input type="text" name="purchase_price[]" onkeypress="return validateNumber(event)" class="form-control purchase-price" value="{{ $odetail->product->purchase_price }}" min="1" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="price[]" onkeypress="return validateNumber(event)" class="form-control price" value="{{ $odetail->price }}" min="1">
                                        </td>
                                        <td>
                                            <input type="hidden" name="product_id[]" value="{{ $odetail->product?->id }}">
                                            <input type="text" name="qty[]" onkeypress="return validateNumber(event)" class="form-control qty" value="{{ $odetail->qty }}">
                                        </td>

                                        <td>
                                            <input type="text" name="amount[]" class="form-control amount" readonly value="{{ $odetail->amount }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3 col-12 col-md-6 offset-md-6">
                        <div class="border shadow-none card">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Sub Total</th>
                                        <td colspan="3"><input type="text" readonly name="sub_total"
                                                class="sub-total no-focus form-control text-end font-weight-bold"
                                                value="{{ $order->sub_total }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount Type</th>
                                        <td>
                                            <select name="discount_type" id="discount_type" class="form-control discount_type">
                                                <option {{ $order->discount_type == "Fixed" ? 'selected' : '' }} value="Fixed">Fixed</option>
                                                <option {{ $order->discount_type == "Percentage" ? 'selected' : '' }} value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>Discount</th>
                                        <td ><input type="text" name="discount"
                                                class="discount no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="{{ $order->discount }}"></td>
                                    </tr>
                                    <tr>
                                        <th>Discount Amount</th>
                                        <td colspan="3"><input type="text" name="discount_amount" readonly
                                                class="discount_amount no-focus form-control text-end font-weight-bold" value="{{ $order->discount_amount }}" readonly></td>
                                    </tr>

                                    <tr>
                                        <th>VAT Type</th>
                                        <td>
                                            <select name="vat_type" id="vat_type" class="form-control vat_type">
                                                <option {{ $order->vat_type == "Fixed" ? 'selected' : '' }} value="Fixed">Fixed</option>
                                                <option {{ $order->vat_type == "Percentage" ? 'selected' : '' }} value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>VAT</th>
                                        <td><input type="text" name="vat"
                                                class="vat no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="{{ $order->vat }}"></td>
                                    </tr>

                                    <tr>
                                        <th>VAT Amount</th>
                                        <td colspan="3"><input type="text" name="vat_amount" readonly
                                                class="vat_amount no-focus form-control text-end font-weight-bold" value="{{ $order->vat_amount }}"></td>
                                    </tr>

                                    <tr>
                                        <th>AIT Type</th>
                                        <td>
                                            <select name="ait_type" id="ait_type" class="form-control ait_type">
                                                <option {{ $order->ait_type == "Fixed" ? 'selected' : '' }} value="Fixed">Fixed</option>
                                                <option {{ $order->ait_type == "Percentage" ? 'selected' : '' }} value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>AIT</th>
                                        <td><input type="text" name="ait"
                                                class="ait no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="{{ $order->ait }}"></td>
                                    </tr>

                                    <tr>
                                        <th>AIT Amount</th>
                                        <td colspan="3"><input type="text" name="ait_amount" readonly
                                                class="ait_amount no-focus form-control text-end font-weight-bold"
                                                value="{{ $order->ait_amount }}"></td>
                                    </tr>


                                    <tr>
                                        <th>Grand Total</th>
                                        <td colspan="3"><input type="text" name="grand_total" readonly
                                                class="border-none grand-total text-end font-weight-bold no-focus form-control"
                                                value="{{ $order->totalamount }}"></td>
                                    </tr>
                                </tbody>
                            </table>
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
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: .375rem !important;
            height: 42px !important;
        }

        .no-focus:focus {
            outline: none;
        }

        .no-border {
            border: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 0px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            getSessionProducts();

            function getSessionProducts() {
                var products = @json(session('products'));

                if (products) {
                    getProducts();
                }
            }

            function getProducts() {
                var url = "{{ route('admin.order.getProducts') }}";

                // $('.tbodyappend').empty();

                // Send AJAX request
                $.ajax({
                    type: "get",
                    url: url,
                    success: function(data) {
                        if (data.html) {
                            $('.tbodyappend').append(data.html);
                            calculateTotal();
                        } else {
                            $('.tbodyappend').append(`<tr><td colspan="7">${data.error}</td></tr>`);
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            function addProduct(product_id, user_id) {
                var url = "{{ route('admin.order.addProduct', ':id') }}".replace(':id', product_id);
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        product_id: product_id,
                        user_id: user_id
                    },
                    success: function(data) {
                        getProducts();
                        changeDiscount();
                        changeVat();
                        changeAit();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            $('#searchCustomer').select2({
                ajax: {
                    url: "{{ route('admin.order.searchCustomer') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                }
            });

            $('#searchReference').select2({
                ajax: {
                    url: "{{ route('admin.order.searchReferance') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        if (data && Array.isArray(data)) {
                            return {
                                results: data
                            };
                        } else {
                            console.error('Invalid data format:', data);
                            return {
                                results: []
                            };
                        }
                    },
                    cache: true,
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                }
            });

            $('#search').select2({
                ajax: {
                    url: "{{ route('admin.order.searchProduct') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                }
            });

            $('#search').on('change', function() {
                var product_id = $(this).val();
                var user_id = $('#searchCustomer').val();
                addProduct(product_id, user_id);
            });

            function sanitizeInput(value) {
                return isNaN(value) ? 0 : value;
            }

            $('.sub-total, .discount, .vat').on('keyup', function() {
                var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));
                var discount = sanitizeInput(parseFloat($('.discount').val()));
                var vat = sanitizeInput(parseFloat($('.vat').val()));
                var grandTotal = subTotal - discount + vat;
                $('.grand-total').val(grandTotal.toFixed(2));
            });

            function calculateTotal() {
                var total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val());
                });
                $('.sub-total').val(total);
                $('.grand-total').val(total);
            }
        });

        $(document).on('click', '.delete', function() {
            $(this).closest('tr').remove();

            var product_id = $(this).closest('tr').find('.product_id').val();

            deleteItem(product_id);

            function deleteItem(product) {
                var url = "{{ route('admin.order.deleteProduct', ':id') }}";
                url = url.replace(':id', product);
                // Send AJAX request
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "product_id": product
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            calculateTotal();
            changeDiscount();
            changeVat();
            changeAit();

            function calculateTotal() {
                var total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val());
                });
                $('.sub-total').val(total);
                $('.grand-total').val(total);
            }
        });

        $(document).on('keyup', '.qty', function() {
            var price = $(this).closest('tr').find('.price').val();
            var qty = $(this).closest('tr').find('.qty').val();
            $(this).closest('tr').find('.amount').val(price * qty);

            var product_id = $(this).closest('tr').find('.product_id').val();
            var thisTotal = parseFloat(price) * parseFloat(qty);
            updateProduct(product_id, 'qty', qty);
            updateProduct(product_id, 'amount', thisTotal);

            calculateTotal();
            changeDiscount();
            changeVat();
            changeAit();

            function calculateTotal() {
                var total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val());
                });
                $('.sub-total').val(total);
                $('.grand-total').val(total);
            }
        });

        $(document).on('keyup', '.price', function() {
            var price = $(this).closest('tr').find('.price').val();
            var qty = $(this).closest('tr').find('.qty').val();
            $(this).closest('tr').find('.amount').val(price * qty);

            var thisTotal = parseFloat(price) * parseFloat(qty);

            var product_id = $(this).closest('tr').find('.product_id').val();
            updateProduct(product_id, 'qty', qty);
            updateProduct(product_id, 'amount', thisTotal);

            calculateTotal();

            changeDiscount();
            changeVat();
            changeAit();

            function calculateTotal() {
                var total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val());
                });
                $('.sub-total').val(total);
                $('.grand-total').val(total);
            }
        });

        function updateProduct(product, key, val) {
            var url = "{{ route('admin.order.updateProduct', ':id') }}";
            url = url.replace(':id', product);

            var key = key;
            var val = val;
            // Send AJAX request
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "product_id": product,
                    "type": key,
                    "val": val
                },
                success: function(response) {
                   // console.log(response);
                },
                error: function(response) {
                   // console.log(response);
                }
            });
        }

        function sanitizeInput(value) {
            return isNaN(value) ? 0 : value;
        }

        $('.sub-total').on('keyup', function() {
            var subTotal = sanitizeInput(parseFloat($(this).val()));
            var discount = sanitizeInput(parseFloat($('.discount').val()));
            var vat = sanitizeInput(parseFloat($('.vat').val()));
            var grandTotal = subTotal - discount + vat;
            $('.grand-total').val(grandTotal.toFixed(2));
        });

        $('.discount').on('keyup', function() {
            changeDiscount();
        });

        $('.vat').on('keyup', function() {
            changeVat();
        });


        $('.ait').on('keyup', function() {
            changeAit();
        });


        $('.discount_type').on('change', function() {
            changeDiscount();
        });

        $('.vat_type').on('change', function() {
            changeVat();
        });


        $('.ait_type').on('change', function() {
            changeAit();
        });

        function typeChange(type, val, amount)
        {
            if (type == 'Percentage') {
                var total = val / 100 * amount;
                return total;
            } else {
                return val;
            }
        }

        function changeDiscount()
        {
            var discountType = $('.discount_type').val();

            var discountValue = sanitizeInput(parseFloat($(".discount").val()));
            var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));

            var discountTotal = typeChange(discountType, discountValue, subTotal);
            $('.discount_amount').val(discountTotal.toFixed(2));

            var grandTotal = subTotal - discountTotal;
            $('.grand-total').val(grandTotal.toFixed(2));

            changeVat();
            changeAit();
        }

        function changeVat()
            {
                var type = $('.vat_type').val();
                var vatValue = sanitizeInput(parseFloat($('.vat').val()));
                var sub_total = sanitizeInput(parseFloat($('.sub-total').val()));
                var discount_amount = sanitizeInput(parseFloat($('.discount_amount').val()));

                var currentTotal = sub_total - discount_amount;

                //var grandtotal = sanitizeInput(parseFloat($('.grand-total').val()));

                var vat_total = typeChange(type, vatValue, currentTotal);

                $('.vat_amount').val(vat_total.toFixed(2));

                var total = currentTotal + vat_total;


                $('.grand-total').val(total.toFixed(2));
            }

            function changeAit()
            {
                var type = $('.ait_type').val();
                var aitValue = sanitizeInput(parseFloat($('.ait').val()));

                var sub_total = sanitizeInput(parseFloat($('.sub-total').val()));
                var discount_amount = sanitizeInput(parseFloat($('.discount_amount').val()));
                var vat_total = sanitizeInput(parseFloat($('.vat_amount').val()));

                var currentTotal = (sub_total - discount_amount);

                // var grandtotal = sanitizeInput(parseFloat($('.grand-total').val()));

                var ait_total = typeChange(type, aitValue, currentTotal);

                $('.ait_amount').val(ait_total.toFixed(2));
                var grandTotal = currentTotal + ait_total;
                $('.grand-total').val(grandTotal.toFixed(2));
            }
    </script>
@endpush

@extends('admin.layouts.app', ['title' => 'Edit Purchase'])
@section('panel')
<form action="{{ route('admin.purchase.update', $purchse->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">Edit Purchase
                <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline-primary btn-sm float-end">
                    <i class="fa fa-list"></i> Purchase List
                </a>
            </h6>
        </div>
        <div class="card-body">
            <div class="row border-bottom">
                <div class="pb-3 col-12 col-md-4">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select select2" id="searchCustomer">
                        <option selected value="{{ $purchse->supplier->id }}">{{ $purchse->supplier->name }}</option>
                    </select>
                </div>
                <div class="pb-3 col-md-4">
                    <div class="form-group">
                        <label class="form-label text-capitalize">@lang('Purchase ID')</label>
                        <input class="form-control" type="text" name="pid" value="{{ $purchse->pid }}" required>
                    </div>
                </div>
                <div class="pb-3 col-md-4">
                    <div class="form-group">
                        <label class="form-label text-capitalize">@lang('Purchase Date')</label>
                        <input class="form-control" type="date" name="date"
                            value="{{ $purchse->date ? $purchse->date : date('Y-m-d') }}" required>
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
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbodyappend">
                                @foreach ($purchse->purchasedetail as $odetail)
                                <tr>
                                    <td>{{ $odetail->product?->id }}</td>
                                    <td>{{ $odetail->product?->name }}</td>
                                    <td>
                                        <input type="hidden" name="order_detial_id[]" value="{{ $odetail->id }}">
                                        <input type="text" name="purchase_price[]"
                                            onkeypress="return validateNumber(event)"
                                            class="form-control purchase-price"
                                            value="{{ $odetail->product->purchase_price }}" min="1" readonly>
                                    </td>
                                    <td>
                                        <input type="hidden" name="product_id[]" value="{{ $odetail->product?->id }}">
                                        <input type="text" name="qty[]" onkeypress="return validateNumber(event)"
                                            class="form-control qty" value="{{ $odetail->qty }}">
                                    </td>
                                    <td>
                                        <input type="text" name="total[]" onkeypress="return validateNumber(event)"
                                            class="form-control sale-price" value="{{ $odetail->price }}" min="1">
                                    </td>
                                    <td>
                                        <input type="text" name="price[]" class="form-control price" readonly
                                            value="{{ $odetail->amount }}">
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
                                    <td>
                                        <input type="text" readonly name="sub_total"
                                            class="sub-total no-focus form-control text-end font-weight-bold"
                                            value="{{ $purchse->sub_total }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td>
                                        <input type="text" name="discount"
                                            class="discount no-focus form-control text-end font-weight-bold"
                                            onkeypress="return validateNumber(event)" value="{{ $purchse->discount }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>VAT (If Apply)</th>
                                    <td>
                                        <input type="text" name="vat"
                                            class="vat no-focus form-control text-end font-weight-bold"
                                            onkeypress="return validateNumber(event)" value="{{ $purchse->vat }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>AIT (If Apply)</th>
                                    <td><input type="text" name="ait"
                                            class="ait no-focus form-control text-end font-weight-bold"
                                            onkeypress="return validateNumber(event)" value="{{ $purchse->ait }}"></td>
                                </tr>
                                <tr>
                                    <th>Transport Cost</th>
                                    <td><input type="text" name="transport_cost"
                                            class="transport_cost no-focus form-control text-end font-weight-bold"
                                            onkeypress="return validateNumber(event)" value="{{ $purchse->transport_cost }}"></td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td>
                                        <input type="text" name="grand_total" readonly
                                            class="border-none grand-total text-end font-weight-bold no-focus form-control"
                                            value="{{ $purchse->totalamount }}">
                                    </td>
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
            var url = "{{ route('admin.purchase.getProducts') }}";
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
            var url = "{{ route('admin.purchase.addProduct', ':id') }}".replace(':id', product_id);
            $.ajax({
                type: "get",
                url: url,
                data: {
                    product_id: product_id,
                    user_id: user_id
                },
                success: function(data) {
                    console.log(data);
                    getProducts();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
        $('#searchCustomer').select2({
            ajax: {
                url: "{{ route('admin.purchase.searchSupplier') }}",
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
        $('#search').select2({
            ajax: {
                url: "{{ route('admin.purchase.searchProduct') }}",
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
            $('.price').each(function() {
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
            var url = "{{ route('admin.purchase.deleteProduct', ':id') }}";
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
        changeTransport();

        function calculateTotal() {
            var total = 0;
            $('.price').each(function() {
                total += parseFloat($(this).val());
            });
            $('.sub-total').val(total);
            $('.grand-total').val(total);
        }
    });
    $(document).on('keyup', '.qty', function() {
        var price = $(this).closest('tr').find('.sale-price').val();
        var qty = $(this).closest('tr').find('.qty').val();
        $(this).closest('tr').find('.price').val(price * qty);
        var product_id = $(this).closest('tr').find('.product_id').val();
        var thisTotal = parseFloat(price) * parseFloat(qty);
        updateProduct(product_id, 'qty', qty);
        updateProduct(product_id, 'sale_price', thisTotal);
        calculateTotal();
        changeDiscount();
        changeVat();
        changeAit();
        changeTransport();

        function calculateTotal() {
            var total = 0;
            $('.price').each(function() {
                total += parseFloat($(this).val());
            });
            $('.sub-total').val(total);
            $('.grand-total').val(total);
        }
    });
    $(document).on('keyup', '.sale-price', function() {
        var price = $(this).closest('tr').find('.sale-price').val();
        var qty = $(this).closest('tr').find('.qty').val();
        $(this).closest('tr').find('.price').val(price * qty);
        var thisTotal = parseFloat(price) * parseFloat(qty);
        var product_id = $(this).closest('tr').find('.product_id').val();
        updateProduct(product_id, 'qty', qty);
        updateProduct(product_id, 'sale_price', thisTotal);
        calculateTotal();
        changeDiscount();
        changeVat();
        changeAit();
        changeTransport();

        function calculateTotal() {
            var total = 0;
            $('.price').each(function() {
                total += parseFloat($(this).val());
            });
            $('.sub-total').val(total);
            $('.grand-total').val(total);
        }
    });

    function updateProduct(product, key, val) {
        var url = "{{ route('admin.purchase.updateProduct', ':id') }}";
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
                console.log(response);
            },
            error: function(response) {
                console.log(response);
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

    $('.transport_cost').on('keyup', function() {
        changeTransport();
    });

    function changeDiscount()
    {
        var discountValue = sanitizeInput(parseFloat($(".discount").val()));
        var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));
        var grandTotal = subTotal - discountValue;
        $('.grand-total').val(grandTotal.toFixed(2));

    }

    function changeVat()
    {
        var vatValue = sanitizeInput(parseFloat($('.vat').val()));

        var discountValue = sanitizeInput(parseFloat($(".discount").val()));
        var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));

        var currentTotal = subTotal - discountValue;

        var grandTotal = currentTotal + vatValue;
        $('.grand-total').val(grandTotal.toFixed(2));
    }

    function changeAit()
    {
        var vatValue = sanitizeInput(parseFloat($('.vat').val()));
        var aitValue = sanitizeInput(parseFloat($('.ait').val()));

        var discountValue = sanitizeInput(parseFloat($(".discount").val()));
        var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));

        var currentTotal = (subTotal - discountValue) + vatValue;

        var grandTotal = currentTotal + aitValue;
        $('.grand-total').val(grandTotal.toFixed(2));
    }

    function changeTransport()
    {
        var transportCost = sanitizeInput(parseFloat($('.transport_cost').val()));
        var vatValue = sanitizeInput(parseFloat($('.vat').val()));
        var aitValue = sanitizeInput(parseFloat($('.ait').val()));

        var discountValue = sanitizeInput(parseFloat($(".discount").val()));
        var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));

        var currentTotal = (subTotal - discountValue) + vatValue + aitValue;

        var grandTotal = currentTotal + transportCost;
        $('.grand-total').val(grandTotal.toFixed(2));

    }
</script>
@endpush

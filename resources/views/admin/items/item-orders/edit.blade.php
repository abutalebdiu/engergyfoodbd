@extends('admin.layouts.app', ['title' => 'Edit Item Order'])
@section('panel')
    <form action="{{ route('admin.items.itemOrder.update', $itemorder->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Edit Item Order <a href="{{ route('admin.items.itemOrder.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i>Item Order
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom mb-3">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Supplier / Customer</label>
                        <select name="supplier_id" class="form-select select2" id="searchCustomer">
                            @if (session('customer'))
                                <option value="{{ session('customer')->id }}">{{ session('customer')->name }}</option>
                            @else
                                <option value="{{ $itemorder->supplier_id }}">{{ $itemorder->supplier->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference Invoice No')</label>
                            <input class="form-control" type="text" name="reference_invoice_no"
                                value="{{ old('reference_invoice_no', $itemorder->reference_invoice_no) }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Purchase Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : $itemorder->date }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Sale Price</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyappend">
                                    @foreach ($itemorder->itemOrderDetail as $odetail)
                                        <tr>
                                            <td>{{ $odetail->product?->id }}</td>
                                            <td>{{ $odetail->product?->name }}</td>
                                            <td>
                                                <input type="hidden" name="order_detial_id[]" value="{{ $odetail->id }}">
                                                <input type="text" name="price[]"
                                                    onkeypress="return validateNumber(event)" class="form-control price"
                                                    value="{{ $odetail->price }}" min="1" readonly>
                                            </td>
                                            <td>
                                                <input type="hidden" name="product_id[]"
                                                    value="{{ $odetail->product?->id }}">
                                                <input type="text" name="qty[]"
                                                    onkeypress="return validateNumber(event)" class="form-control qty"
                                                    value="{{ $odetail->qty }}">
                                            </td>

                                            <td>
                                                <p>{{ $odetail->product?->unit?->name }}</p>
                                            </td>

                                            <td>
                                                <input type="text" name="amount[]" class="form-control amount" readonly
                                                    value="{{ $odetail->total }}">
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

                    <div class="mt-3 col-12 col-md-4">
                        <div class="border shadow-none card">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Sub Total</th>
                                        <td><input type="text" readonly name="sub_total"
                                                class="sub-total no-focus form-control text-end font-weight-bold"
                                                value="{{ $itemorder->subtotal }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td><input type="text" name="discount"
                                                class="discount no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)"
                                                value="{{ $itemorder->discount }}"></td>
                                    </tr>
                                    <tr>
                                        <th>Transport Cost</th>
                                        <td><input type="text" name="transport_cost"
                                                class="transport_cost no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)"
                                                value="{{ $itemorder->transport_cost }}"></td>
                                    </tr>
                                    <tr>
                                        <th>Labour Cost</th>
                                        <td><input type="text" name="labour_cost"
                                                class="labour_cost no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)"
                                                value="{{ $itemorder->labour_cost }}"></td>
                                    </tr>
                                    <tr>
                                        <th>Grand Total</th>
                                        <td><input type="text" name="grand_total" readonly
                                                class="border-none grand-total text-end font-weight-bold no-focus form-control"
                                                value="0"></td>
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
                        <a href="{{ route('admin.items.itemOrder.index') }}"
                            class="btn btn-outline-info float-start">Back</a>
                        <input type="submit" class="btn btn-primary float-end"
                            onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                            value="@lang('Submit')">
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

            window.onload = function() {
                getSessionProducts();
            };

            function getSessionProducts() {
                var products = @json(session('items'));

                if (products) {
                    getProducts();
                }

                changeDiscount();

                changeTransport();
            }

            function getProducts() {
                var url = "{{ route('admin.items.itemOrder.getProducts') }}";
                // Send AJAX request

                $.ajax({
                    type: "get",
                    url: url,

                    success: function(data) {

                        if (data.error) {
                            $('.tbodyappend').html(`<tr><td colspan="7">${data.error}</td></tr>`);
                        }

                        if (data.html) {
                            $('.tbodyappend').append(data.html);
                            calculateTotal();

                            function calculateTotal() {
                                var total = 0;
                                $('.amount').each(function() {
                                    total += parseFloat($(this).val());
                                });
                                $('.sub-total').val(total);
                                $('.grand-total').val(total);
                            }
                        } else {
                            $('.tbodyappend').html('');
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            function addProduct(product_id, user_id) {
                var url = "{{ route('admin.items.itemOrder.addProduct', ':id') }}";

                url = url.replace(':id', product_id);
                // Send AJAX request
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
                        changeTransport();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            $('#searchCustomer').select2({
                ajax: {
                    url: "{{ route('admin.items.itemOrder.searchSupplier') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        console.log(data);
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
                    url: "{{ route('admin.items.itemOrder.searchProduct') }}",
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


            $('#search').on('change', function() {
                var product_id = $(this).val();
                var user_id = $('#searchCustomer').val();
                addProduct(product_id, user_id);
            });

            $(document).on('click', '.delete', function() {
                $(this).closest('tr').remove();

                var product_id = $(this).closest('tr').find('.product_id').val();

                deleteItem(product_id);

                function deleteItem(product) {
                    var url = "{{ route('admin.items.itemOrder.deleteProduct', ':id') }}";
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
                            var itemClass = $(".item_id[data-id='" + product_id + "']");
                            itemClass.removeClass("active");

                        },
                        error: function(response) {

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
                var url = "{{ route('admin.items.itemOrder.updateProduct', ':id') }}";
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


            $('.transport_cost').on('keyup', function() {
                changeTransport();
            });


            $('.labour_cost').on('keyup', function() {
                changeLaborCost();
            });

            function changeDiscount() {
                var discountValue = sanitizeInput(parseFloat($(".discount").val()));
                var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));
                var grandTotal = subTotal - discountValue;
                $('.grand-total').val(grandTotal.toFixed(2));

            }


            function changeTransport() {
                var transportCost = sanitizeInput(parseFloat($('.transport_cost').val()));
                var discountValue = sanitizeInput(parseFloat($(".discount").val()));
                var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));
                var currentTotal = (subTotal - discountValue);
                var grandTotal = currentTotal + transportCost;
                $('.grand-total').val(grandTotal.toFixed(2));

            }


            function changeLaborCost() {
                var laborCost = sanitizeInput(parseFloat($('.labour_cost').val()));
                var transportCost = sanitizeInput(parseFloat($('.transport_cost').val()));
                var discountValue = sanitizeInput(parseFloat($(".discount").val()));
                var subTotal = sanitizeInput(parseFloat($('.sub-total').val()));
                var currentTotal = (subTotal - discountValue) + transportCost;
                var grandTotal = currentTotal + laborCost;
                $('.grand-total').val(grandTotal.toFixed(2));
            }

            $(".item_id").on('click', function() {
                var item_id = $(this).data('id');
                var user_id = parseInt($("#searchCustomer").val());


                $(this).addClass('active');

                if (user_id === 0) {
                    alert('Please select a valid user');
                    return false;
                }

                addProduct(item_id, user_id);
            });

        });
    </script>
@endpush

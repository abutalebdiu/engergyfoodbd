@extends('admin.layouts.app', ['title' => 'Add New Purchase'])
@section('panel')
    <form action="{{ route('admin.purchase.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Add New Purchase <a href="{{ route('admin.purchase.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Purchase
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select select2" id="searchCustomer">
                            @if(session('customer'))
                                <option value="{{ session('customer')->id }}">{{ session('customer')->name }}</option>
                            @else
                                <option value="">Select Supplier</option>
                            @endif
                        </select>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference Invoice No')</label>
                            <input class="form-control" type="text" name="reference_invoice_no" value="{{ old('reference_invoice_no') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Purchase Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
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
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyappend">

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
                                        <td><input type="text" readonly name="sub_total"
                                                class="sub-total no-focus form-control text-end font-weight-bold"
                                                value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td><input type="text" name="discount"
                                                class="discount no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>AIT (If Apply)</th>
                                        <td><input type="text" name="ait"
                                                class="ait no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>VAT (If Apply)</th>
                                        <td><input type="text" name="vat"
                                                class="vat no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>Transport Cost</th>
                                        <td><input type="text" name="transport_cost"
                                                class="transport_cost no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
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
                        <a href="{{ route('admin.purchase.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')
                        </button>
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
                var products = @json(session('products'));
                getProducts();
                changeDiscount();
                changeVat();
                changeAit();
                changeTransport();
            }

            function getProducts() {
                var url = "{{ route('admin.purchase.getProducts') }}";
                // Send AJAX request

                $('.tbodyappend').empty();
                $.ajax({
                    type: "get",
                    url: url,
                    // beforeSend: function() {
                    //     $('.tbodyappend').html(`<tr><td colspan="2"><div class="spinner-border d-flex justify-content-center align-items-center" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>`);
                    // },
                    success: function(data) {

                        if(data.error){
                            $('.tbodyappend').html(`<tr><td colspan="7">${data.error}</td></tr>`);
                        }

                        if (data.html) {
                            $('.tbodyappend').html(data.html);
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
                var url = "{{ route('admin.purchase.addProduct', ':id') }}";

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
                        changeVat();
                        changeAit();
                        changeTransport();
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
                    url: "{{ route('admin.purchase.searchProduct') }}",
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
        });
    </script>
@endpush

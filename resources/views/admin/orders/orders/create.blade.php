@extends('admin.layouts.app', ['title' => 'Add New Order'])
@section('panel')
    <form action="{{ route('admin.order.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Add New Order <a href="{{ route('admin.order.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Order
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select select2" id="searchCustomer">
                            @if (session('customer'))
                                <option value="{{ session('customer')->id }}">{{ session('customer')->name }}</option>
                            @else
                                <option value="">Select Customer</option>
                            @endif
                        </select>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference')</label>
                            <input type="text" class="form-control" name="media">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Order Date')</label>
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
                    <div class="pb-3 col-12 col-md-12 border-bottom ">
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
                                                value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount Type</th>
                                        <td>
                                            <select name="discount_type" id="discount_type" class="form-control discount_type">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>Discount</th>
                                        <td ><input type="text" name="discount"
                                                class="discount no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>Discount Amount</th>
                                        <td colspan="3"><input type="text" name="discount_amount" readonly
                                                class="discount_amount no-focus form-control text-end font-weight-bold" value="0" readonly></td>
                                    </tr>

                                    <tr>
                                        <th>VAT Type</th>
                                        <td>
                                            <select name="vat_type" id="vat_type" class="form-control vat_type">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>VAT</th>
                                        <td><input type="text" name="vat"
                                                class="vat no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>

                                    <tr>
                                        <th>VAT Amount</th>
                                        <td colspan="3"><input type="text" name="vat_amount" readonly
                                                class="vat_amount no-focus form-control text-end font-weight-bold" value="0"></td>
                                    </tr>

                                    <tr>
                                        <th>AIT Type</th>
                                        <td>
                                            <select name="ait_type" id="ait_type" class="form-control ait_type">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percentage">Percentage</option>
                                            </select>
                                        </td>

                                        <th>AIT</th>
                                        <td><input type="text" name="ait"
                                                class="ait no-focus form-control text-end font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>

                                    <tr>
                                        <th>AIT Amount</th>
                                        <td colspan="3"><input type="text" name="ait_amount" readonly
                                                class="ait_amount no-focus form-control text-end font-weight-bold"
                                                value="0"></td>
                                    </tr>


                                    <tr>
                                        <th>Grand Total</th>
                                        <td colspan="3"><input type="text" name="grand_total" readonly
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
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-info float-start">Back</a>
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
                var products = @json(session('order_products'));
                getProducts();
                changeDiscount();
                changeVat();
                changeAit();
            }

            function getProducts() {
                var url = "{{ route('admin.order.getProducts') }}";
                // Send AJAX request

                $('.tbodyappend').empty();
                $.ajax({
                    type: "get",
                    url: url,
                    // beforeSend: function() {
                    //     $('.tbodyappend').html(`<tr><td colspan="2"><div class="spinner-border d-flex justify-content-center align-items-center" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>`);
                    // },
                    success: function(data) {

                        if (data.error) {
                            $('.tbodyappend').html(`<tr><td colspan="7">${data.error}</td></tr>`);
                        }

                        if (data.html) {
                            $('.tbodyappend').html(data.html);
                            calculateTotal();

                            function calculateTotal() {
                                var total = 0;
                                $('.price').each(function() {
                                    total += parseFloat($(this).val());
                                });
                                $('.sub-total').val(total.toFixed(2));
                                $('.grand-total').val(total.toFixed(2));
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
                var url = "{{ route('admin.order.addProduct', ':id') }}";

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

                // changeVat();
                // changeAit();
            }

            function changeVat()
            {
                var type = $('.vat_type').val();
                var vatValue = sanitizeInput(parseFloat($('.vat').val()));
                var sub_total = sanitizeInput(parseFloat($('.sub-total').val()));
                var discount_amount = sanitizeInput(parseFloat($('.discount_amount').val()));
                var ait_amount = sanitizeInput(parseFloat($('.ait_amount').val()));

                var currentTotal = sub_total - discount_amount;

                //var grandtotal = sanitizeInput(parseFloat($('.grand-total').val()));

                var vat_total = typeChange(type, vatValue, currentTotal);

                $('.vat_amount').val(vat_total.toFixed(2));

                var total = currentTotal + vat_total + ait_amount;


                $('.grand-total').val(total.toFixed(2));
            }

            var current_subtotal = (sanitizeInput(parseFloat($('.sub-total').val())) - sanitizeInput(parseFloat($('.discount_amount').val())));

            function changeAit()
            {
                var type = $('.ait_type').val();
                var aitValue = sanitizeInput(parseFloat($('.ait').val()));

                var sub_total = sanitizeInput(parseFloat($('.sub-total').val()));
                var discount_amount = sanitizeInput(parseFloat($('.discount_amount').val()));
                var vat_total = sanitizeInput(parseFloat($('.vat_amount').val()));

                var currentTotal = sub_total - discount_amount;
                var ait_total = typeChange(type, aitValue, currentTotal);

                $('.ait_amount').val(ait_total.toFixed(2));
                var grandTotal = currentTotal + ait_total + vat_total;
                $('.grand-total').val(grandTotal.toFixed(2));
            }

        });
    </script>
@endpush

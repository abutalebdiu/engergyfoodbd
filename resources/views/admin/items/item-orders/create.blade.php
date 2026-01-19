@extends('admin.layouts.app', ['title' => __('New Order')])
@section('panel')
    <form action="{{ route('admin.items.itemOrder.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">@lang('New Order') <a href="{{ route('admin.items.itemOrder.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> @lang('Order List')</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom mb-4">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">@lang('Suppliers')</label>
                        <select name="supplier_id" class="form-select select2" id="searchCustomer">
                            @if (session('customer'))
                                <option value="{{ session('customer')->id }}">{{ session('customer')->name }}</option>
                            @else
                                <option value="0">@lang('Select Supplier')</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Reference Invoice No')</label>
                            <input class="form-control" type="text" name="reference_invoice_no"
                                value="{{ old('reference_invoice_no') }}">
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

                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <label for="">@lang('Items')</label>
                        <select name="item_category_id" id="item_category_id" class="form-select select2 item_category_id">
                            <option value="">@lang('Select Item Category')</option>
                            @foreach ($itemcategories as $itemcategory)
                                <option data-items="{{ $itemcategory->items }}" value="{{ $itemcategory->id }}">
                                    {{ $itemcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-8 mb-3">
                        <label for="">@lang('Items')</label>
                        <select name="search" id="search" class="form-select select2 search">
                            <option value="">@lang('Select Item')</option>

                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Item')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Total')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody class="tbodyappend">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">

                        <div class="border shadow-none card">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>@lang('Sub Total')</th>
                                        <td><input type="text" readonly name="sub_total"
                                                class="sub-total no-focus form-control text-start font-weight-bold"
                                                value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Discount')</th>
                                        <td><input type="text" name="discount"
                                                class="discount no-focus form-control text-start font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Transport Cost')</th>
                                        <td><input type="text" name="transport_cost"
                                                class="transport_cost no-focus form-control text-start font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Labour Cost')</th>
                                        <td><input type="text" name="labour_cost"
                                                class="labour_cost no-focus form-control text-start font-weight-bold"
                                                onkeypress="return validateNumber(event)" value="0"></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Grand Total')</th>
                                        <td><input type="text" name="grand_total" readonly
                                                class="border-none grand-total text-start font-weight-bold no-focus form-control"
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
                            class="btn btn-outline-info float-start">@lang('Back')</a>
                        <input type="submit" class="btn btn-primary float-end"
                            onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                            value="@lang('Submit')">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@include('components.select2')

@push('script')
    <script>
        $('[name=item_category_id]').on('change', function() {
            var items = $(this).find('option:selected').data('items');
            var option = '<option value="">Select Item</option>';
            $.each(items, function(index, value) {

                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    value.name + "</option>";
            });

            $('select[name=search]').html(option);
        }).change();
    </script>
    <script>
        $(document).ready(function() {

            function sanitizeInput(value) {
                return isNaN(value) ? 0 : value;
            }

            window.onload = function() {
                getSessionProducts();
            };

            function getSessionProducts() {
                getProducts();
                changeDiscount();
                changeTransport();
            }

            function getProducts() {
                var url = "{{ route('admin.items.itemOrder.getProducts') }}";

                $('.tbodyappend').empty();

                $.ajax({
                    type: "get",
                    url: url,
                    beforeSend: function() {
                        $('.tbodyappend').append('<tr><td colspan="7">Loading...</td></tr>');
                    },
                    success: function(data) {

                        if (data.error) {
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


            $('.sub-total').on('keyup', function() {
                var subTotal = sanitizeInput(parseFloat($(this).val()));
                var discount = sanitizeInput(parseFloat($('.discount').val()));
                var grandTotal = subTotal - discount;
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
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }

            $('#search').on('change', function() {
                var product_id = $(this).val();
                var user_id = $('#searchCustomer').val();
                addProduct(product_id, user_id);
            });

        });
    </script>
@endpush




@include('components.select2')

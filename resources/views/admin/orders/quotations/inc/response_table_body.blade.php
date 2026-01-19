@foreach ($products as $product)

<tr>
    <td>{{ $product->id }}</td>
    <td>{{ $product->name }}</td>
    <td>
        <input type="text" name="purchase_price[]" onkeypress="return validateNumber(event)" class="form-control purchase-price" value="{{ $product->purchase_price }}" min="1" readonly>
    </td>
    <td>
        <input type="text" name="price[]" class="form-control price" onkeypress="return validateNumber(event)" value="{{ $product->sale_price }}">
    </td>
    <td>
        <input type="hidden" name="product_id[]" class="product_id" value="{{ $product->id }}">
        <input type="text" name="qty[]" onkeypress="return validateNumber(event)" class="form-control qty" value="{{ $product->qty ?? 1 }}">
    </td>
    <td>
        <input type="text" name="amount[]"  readonly class="form-control amount" value="{{ $product->sale_price * $product->qty }}" min="1">
    </td>
    <td>
        <button type="button" class="btn btn-danger delete">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
@endforeach
<script>
    $(document).ready(function() {
        calculateTotal();

        function calculateTotal() {

            var total = 0;
            $('.amount').each(function() {
                total += parseFloat($(this).val());
            });

            $('.sub-total').val(total);
            $('.grand-total').val(total);
        }

        $(document).on('click', '.delete', function() {
            $(this).closest('tr').remove();

            var product_id = $(this).closest('tr').find('.product_id').val();

            deleteItem(product_id);

            function deleteItem(product) {
                var url = "{{ route('admin.quotations.deleteProduct', ':id') }}";
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
            var url = "{{ route('admin.quotations.updateProduct', ':id') }}";
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

                var currentTotal = (sub_total - discount_amount) + vat_total;

                // var grandtotal = sanitizeInput(parseFloat($('.grand-total').val()));

                var ait_total = typeChange(type, aitValue, currentTotal);

                $('.ait_amount').val(ait_total.toFixed(2));
                var grandTotal = currentTotal + ait_total;
                $('.grand-total').val(grandTotal.toFixed(2));
            }
    });
</script>



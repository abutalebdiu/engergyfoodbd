@foreach ($products as $product)
<tr>
    <td>{{ $product->id }}</td>
    <td>{{ $product->name }}</td>
    <td>
        <input type="text" name="price[]" class="form-control price" value="{{ $product->purchase_price }}">
    </td>
    <td>
        <input type="hidden" name="product_id[]" class="product_id" value="{{ $product->id }}">
        <input type="text" name="qty[]" onkeypress="return validateNumber(event)" class="form-control qty" value="{{ $product->qty ?? 1 }}">
    </td>
    <td>
        <input type="text" name="amount[]" onkeypress="return validateNumber(event)" class="form-control amount" value="{{ $product->purchase_price / $product->qty }}" min="1">
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
            changeTransport();

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
            changeTransport();

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



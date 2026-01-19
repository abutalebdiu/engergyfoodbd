@if (!empty($items))
    @foreach ($items as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td style="text-align: left">{{ $product->name }}</td>
            <td>
                <input type="hidden" name="product_id[]" class="product_id" value="{{ $product->id }}">
                <input type="text" name="qty[]" class="form-control qty" value="{{ $product->qty ?? 1 }}">
            </td>
            <td>
                <input type="text" name="amount[]" onkeypress="return validateNumber(event)" class="form-control amount"
                    value="{{ $product->price * $product->qty }}" min="1">
            </td>

            <td>
                <button type="button" class="btn btn-danger delete">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    @endforeach
@endif


<script>
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
                var url = "{{ route('admin.items.itemOrder.deleteProduct', ':id') }}";
                url = url.replace(':id', product);

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


        $(document).on('keyup', '.amount', function() {
            var amount = $(this).closest('tr').find('.amount').val();
            var thisTotal = parseFloat(amount);
            var product_id = $(this).closest('tr').find('.product_id').val();
            updateProduct(product_id, 'amount', thisTotal);

            calculateTotal();
            changeDiscount();
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

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

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
</script>

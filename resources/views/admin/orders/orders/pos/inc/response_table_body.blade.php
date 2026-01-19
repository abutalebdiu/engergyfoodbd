
<tr>
    <td>{{ $product->id }}</td>
    <td>{{ $product->name }}</td>    
    
    <td>
        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
        <input type="text" name="qty[]" onkeypress="return validateNumber(event)" class="form-control qty" value="1">
    </td>
    
    <td>
        <button type="button" class="btn btn-danger delete">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>

<script>
    $(document).ready(function() {
        $(document).on('click', '.delete', function() {
            $(this).closest('tr').remove();

            calculateTotal();

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

            calculateTotal();

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

            calculateTotal();

            function calculateTotal() {
                var total = 0;
                $('.price').each(function() {
                    total += parseFloat($(this).val());
                });
                $('.sub-total').val(total);
                $('.grand-total').val(total);
            }
        });
    });
</script>



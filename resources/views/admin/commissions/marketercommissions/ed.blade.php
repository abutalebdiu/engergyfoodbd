@extends('admin.layouts.app', ['title' => 'Commission Setup'])
@section('panel')
    <form action="{{ route('admin.commissioninvoice.update', ['id' => $user->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Commission Setup for <a href="#">{{ $user->name }}</a>
                    <div class="float-end">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control commission" value="" placeholder="commission"
                                aria-label="commission">
                            <button class="btn btn-outline-secondary submit" type="button">Submit</button>
                        </div>
                    </div>
                </h6>
            </div>c
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%">@lang('SL')</th>
                                    <th style="width: 20%">@lang('Product Name')</th>
                                    <th style="width: 15%">@lang('Price')</th>
                                    <th style="width: 15%">@lang('Commission')</th>
                                    <th style="width: 25%">@lang('Type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ en2bn($loop->iteration) }}</td>
                                        <td class="text-capitalize">
                                            <label class="form-label text-capitalize"> {{ $product->name }}</label>
                                            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        </td>
                                        <td class="">
                                            <input class="form-control" type="text" name="price[]"
                                                value="{{ en2bn($product->productCommission?->price ?? $product->sale_price) }}"
                                                required>
                                        </td>
                                        <td class="text-end">
                                            <input class="form-control amount" type="text" name="amount[]"
                                                value="{{ en2bn($product->productCommission?->amount ?? 6) }}" required>
                                        </td>
                                        <td>
                                            <select class="form-select" name="type[]" required>
                                                <option
                                                    {{ $product->productCommission?->type == 'Percentage' ? 'selected' : '' }}
                                                    value="Percentage">@lang('Percentage')</option>
                                                <option {{ $product->productCommission?->type == 'Flat' ? 'selected' : '' }}
                                                    value="Flat">@lang('Flat')</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="#" class="btn btn-outline-info float-start">Back</a>
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
        .table tr td.size-50 {
            width: 50%;
        }
    </style>
@endpush


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the quantity inputs
            const qtyInputs = document.querySelectorAll('input[name="price[]"]');
            const amountInputs = document.querySelectorAll('input[name="amount[]"]');

            // Add event listeners to each input
            qtyInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    // Prevent form submission on Enter key press and move to the next input
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to next quantity input on Down arrow key press
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to previous quantity input on Up arrow key press
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevInput = qtyInputs[index - 1];
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                });
            });

            // Add event listeners to each input
            amountInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    // Prevent form submission on Enter key press and move to the next input
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = amountInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to next quantity input on Down arrow key press
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextInput = amountInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to previous quantity input on Up arrow key press
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevInput = amountInputs[index - 1];
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.submit').click(function() {
                // Get the value of the commission input
                var commissionValue = $('.commission').val();

                // Update all amount input fields with the commission value
                $('.amount').each(function() {
                    $(this).val(commissionValue);
                });
            });
        });
    </script>
@endpush

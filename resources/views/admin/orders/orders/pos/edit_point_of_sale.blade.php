@extends('admin.layouts.app', ['title' => __('Edit Order')])
@section('panel')
    <form action="{{ route('admin.order.pos.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('Edit Order') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-4 row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">@lang('Customer')</label>
                            <div class="input-group">
                                <select name="customer_id" class="form-select select2" id="searchCustomer" required>
                                    <option value="{{ $order->customer_id }}"> {{ $order->customer?->name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-4 row">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($products->chunk(30) as $products)
                                <div class="col-12 col-md-4">
                                    <table>
                                        <thead>
                                            <tr class="border-bottom">
                                                <th style="width: 10%">@lang('SL No')</th>
                                                <th style="width: 70%">@lang('Product')</th>
                                                <th>@lang('Quantity')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr>
                                                    <td>{{ en2bn($i++) }} - </td>
                                                    <td><input type="hidden" name="product_id[]"
                                                            id="product_name_{{ $key }}"
                                                            value="{{ $product->id }}" class="border-0 form-control"
                                                            readonly>
                                                        <input type="text" value="{{ $product->name }}"
                                                            class="border-0 form-control" readonly>
                                                    </td>

                                                    <td>
                                                        <input type="text" name="product_qty[]"
                                                            id="product_qty_{{ $key }}"
                                                            value="{{ optional($order->orderdetail->where('product_id', $product->id)->first())->qty ?? 0.0 }}"
                                                            class="border form-control qty" />
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-12"></div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary float-end" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .form-control:focus {
            border-color: red !important;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the quantity inputs
            const qtyInputs = document.querySelectorAll('input[name="product_qty[]"]');

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
        });
    </script>
@endpush

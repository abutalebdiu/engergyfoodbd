@extends('admin.layouts.app', ['title' => 'Add New Item Stock'])
@section('panel')
    <form action="{{ route('admin.itemtstock.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Add New Item Stock <a href="{{ route('admin.itemtstock.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Item Settlement List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Month') <span class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-control" required>
                                <option value="">--Select Month--</option>
                                @foreach ($months as $month)
                                    <option {{ Date('m') == $month->id ? 'selected' : '' }} value="{{ $month->id }}">
                                        {{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="border-bottom">
                                        <th>@lang('Item Name')</th>
                                        <th>@lang('Last Month Stock')</th>
                                        <th>@lang('Purchase')</th>
                                        <th>@lang('Item Use')</th>
                                        <th>@lang('Production Loss')</th>
                                        <th>@lang('Current stock')</th>
                                        <th>@lang('Physical stock')</th>
                                        <th>@lang('Different')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($itemswithcategories as $departmentId => $products)
                                        @php
                                            $departmentName = optional($products->first()->category)->name;
                                        @endphp
                                        <tr>
                                            <td colspan="8" class="font-weight-bold text-primary text-start">
                                                {{ $departmentName ?: 'No Category' }}
                                            </td>
                                        </tr>

                                        @foreach ($products as $key => $product)
                                            <tr class="product-row">
                                                <input type="hidden" name="item_id[]" value="{{ $product->id }}">
                                                <td style="text-align: left"> {{ en2bn($i++) }} - {{ $product->name }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ round($product->getopeningstock($product->id), 2) }}</td>
                                                <td style="text-align: center">
                                                    {{ $product->getpurchasevalue($product->id) }}</td>
                                                <td style="text-align: center">
                                                    {{ $product->getmakeproductionvalue($product->id) + $product->getproductppstock($product->id) + $product->getproductboxstock($product->id) + $product->getproductstrikerstock($product->id) }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $product->productionloss($product->id) }} </td>
                                                <td style="text-align: center">
                                                    {{ round($product->stock($product->id), 2) }}
                                                    <input type="hidden" class="currentstock"
                                                        value="{{ round($product->stock($product->id), 2) }}">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                                    <input type="text" name="physical_stock[]" value=""
                                                        class="physical_stock" size="7" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="qty different" size="7" readonly>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
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
                        <a href="{{ route('admin.productstock.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary mx-1">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>

    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Bind the input event to all qty inputs
            $('.physical_stock').on('input', function() {
                // Find the closest row
                var row = $(this).closest('.product-row');

                // Get the current stock value
                var currentStock = parseFloat(row.find('.currentstock').val()) || 0;

                // Get the qty value
                var physical_stock = parseFloat($(this).val()) || 0;

                // Calculate the difference
                var difference = currentStock - physical_stock;

                // Set the difference value in the different input
                row.find('.different').val(difference);
            });
        });
    </script>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the quantity inputs
            const qtyInputs = document.querySelectorAll('input[name="physical_stock[]"]');

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

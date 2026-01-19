@extends('admin.layouts.app', ['title' => 'Add New Product Stock'])
@section('panel')
    <form action="{{ route('admin.productstock.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Add New Product Stock <a href="{{ route('admin.productstock.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Stock
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Date') <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
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
                                        <th>@lang('Product')</th>
                                        <th>Last Month <br> Stock</th>
                                        <th>@lang('Production')</th>
                                        <th>@lang('Sales')</th>
                                        <th>@lang('Return')</th>
                                        <th>Stock <br> Damage</th>
                                        <th>Customer <br> Damage</th>
                                        <th>System Stock</th>
                                        <th>System Different</th>
                                        <th>Current <br> stock</th>
                                        <th>@lang('Physcial Stock') <span class="text-danger">*</span></th>
                                        <th>@lang('Difference ')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($productswithgroupes as $departmentId => $products)
                                        @php
                                            $departmentName = optional($products->first()->department)->name;
                                        @endphp
                                        <tr>
                                            <td colspan="12" class="font-weight-bold text-primary text-start">
                                                {{ $departmentName ?: 'No Department' }}
                                            </td>
                                        </tr>
                                        @foreach ($products as $key => $product)
                                            @php
                                                $systotal =  $product->getopeningstock($product->id)
                                                            + $product->getproductionvalue($product->id)
                                                            + $product->getcustomerorderreturn($product->id)
                                                            - $product->getsalevalue($product->id)
                                                            - $product->getproductdamage($product->id)
                                                            - $product->getcustomerproductdamage($product->id);
                                            
                                            @endphp
                                         
                                            <tr class="product-row">
                                                <td class="text-start">{{ en2bn($i++) }} - {{ $product->name }}</td>
                                                <td class="text-center">{{ $product->getopeningstock($product->id) }}</td>
                                                <td class="text-center">{{ $product->getproductionvalue($product->id) }}</td>
                                                <td class="text-center">{{ $product->getsalevalue($product->id) }}</td>
                                                <td class="text-center">{{ $product->getcustomerorderreturn($product->id) }}</td>
                                                <td class="text-center">{{ $product->getproductdamage($product->id) }}</td>
                                                <td class="text-center">
                                                    {{ $product->getcustomerproductdamage($product->id) }}</td>
                                                <td> {{  $systotal }}  </td>
                                                <td>{{ $product->getstock($product->id) - $systotal }}  </td>
                                                <td>
                                                    {{ $product->getstock($product->id) }}
                                                    <input type="hidden" value="{{ $product->getStock($product->id) }}"
                                                        class="currentstock" readonly>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                                    <input type="text" name="physical_stock[]"
                                                        value=""
                                                        class="physical_stock" size="7" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="qty different" size="5" readonly>
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
    <style></style>
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


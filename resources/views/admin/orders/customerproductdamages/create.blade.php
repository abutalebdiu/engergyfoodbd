@extends('admin.layouts.app', ['title' => __('Add Customer Damage Product')])
@section('panel')
    <form action="{{ route('admin.customerproductdamage.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">@lang('Add Customer Damage Product')

                    <a href="{{ route('admin.customerproductdamage.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> @lang('Customers Products Damage List')</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">@lang('Customer') <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select select2" required>
                            <option value="">@lang('Search Customer')</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pb-3 col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Date')  <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="pb-3 mb-2 col-12 col-md-8">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Note')</label>
                            <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    
                    @foreach ($productswithgroupes as $departmentId => $products)
                        @php
                            $departmentName = optional($products->first()->department)->name;
                        @endphp
                        <div>
                                {{ $departmentName ?: 'No Department' }}
                        </div>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($products->chunk(ceil($products->count() / 2)) as $chunk)
                            <div class="col-12 col-md-6">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th style="width: 10%">@lang('SL No')</th>
                                            <th style="width: 70%">@lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chunk as $key => $product)
                                        
                                            <tr  style="border-bottom: 2px solid #21b2c1;">
                                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                                <td>{{ en2bn($i++) }}  </td>
                                                <td style="text-align: left">
                                                    {{ $product->name }}
                                                </td>
                                                <td><input type="text" name="product_qty[]"
                                                        id="product_qty_{{ $key }}"  value=""
                                                        class="border form-control qty"></td>

                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach
                          

                    <div class="col-12 col-md-7 mt-3">
                        <a href="{{ route('admin.customerproductdamage.index') }}"
                            class="btn btn-outline-info float-start">Back</a>
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
         
        .form-control:focus {
            border:3px solid  red !important;
        }
        .form-control{
            border-color:#000 !important;
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


@include('components.select2')

@extends('admin.layouts.app', ['title' => __('New Order Return')])
@section('panel')
    <form action="{{ route('admin.orderreturn.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">@lang('New Order Return') @if ($order)
                        - {{ $order->oid }}
                    @endif
                    <a href="{{ route('admin.orderreturn.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                            class="fa fa-list"></i> @lang('Order Return')</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row border-bottom">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">@lang('Customer')</label>
                        @if ($order)
                            <input type="text" class="form-control" value="{{ $order->customer->name }}" readonly>
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                        @else
                            <select name="customer_id" id="customer_id" class="form-select select2" required>
                                <option value="">@lang('Select Customer')</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"> {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Date')</label>
                            <input class="form-control" type="date" name="date" @if($order) value="{{ $order->date }}"  @else  value="{{ Date('Y-m-d') }}" @endif>
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
                            <table>
                                <thead>
                                    <tr class="border-bottom">
                                        <th style="width: 10%">@lang('SL No')</th>
                                        <th style="width: 70%">@lang('Product')</th>
                                        <th>@lang('Quantity')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($chunk as $key => $product)
                                    <tr style="border-bottom: 2px solid #21b2c1;">
                                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        <td>{{ en2bn($i++) }} </td>
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
                        
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-info float-start">Back</a>
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
            border-color: red !important;
        }
    </style>
@endpush

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

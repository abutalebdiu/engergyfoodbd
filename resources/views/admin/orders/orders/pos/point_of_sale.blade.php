@extends('admin.layouts.app', ['title' => __('New Order')])
@section('panel')
    <form id="ponitOfSale" action="{{ route('admin.order.pos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('New Order') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-4 border-bottom row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Customer')</label>
                            <div class="input-group">
                                <select name="customer_id" class="form-select select2" id="searchCustomer" required>
                                    <option value="">@lang('Search Customer')</option>
                                    @foreach ($customers as $customer)
                                        <option
                                            @if ($quotation) {{ $quotation->customer_id == $customer->id ? 'selected' : '' }} @endif
                                            value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="oid" @if($quotation) value="{{ $quotation->qid }}" @else value="" @endif />
                                <input type="hidden" name="quotation_id" @if($quotation) value="{{ $quotation->id }}" @else value="" @endif />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Sales Man')</label>
                            <div class="input-group">
                                <select name="salesman_id" class="form-select salesman_id select2" id="salesman_id"
                                    >
                                    <option value="">@lang('Search Sales Man')</option>
                                    @foreach ($employees->where('department_id',27) as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Driver')</label>
                            <div class="input-group">
                                <select name="driver_id" class="form-select driver_id select2" id="driver_id">
                                    <option value="">@lang('Search Driver')</option>
                                    @foreach ($employees->where('department_id',26) as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <input type="date" name="date" @if($quotation) value="{{ $quotation->date }}"  @else value="{{ old('date') ? old('date') : Date('Y-m-d') }}" @endif
                                class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($productswithgroupes as $departmentId => $products)
                        @php
                            $departmentName = optional($products->first()->department)->name;
                        @endphp

                        <div class="col-12">
                            <h5 class="font-weight-bold text-primary mb-2">
                                {{ $departmentName ?: 'No Department' }}
                            </h5>
                        </div>

                        @foreach ($products->chunk(ceil($products->count() / 2)) as $chunk)
                            <div class="col-12 col-md-6">
                                <table class="table table-bordered" border="2">
                                    <thead>
                                        <tr>
                                            <th style="width: 70%">@lang('SL No') - @lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($chunk as $key => $product)
                                            <tr style="border-bottom: 2px solid #21b2c1;">
                                                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                                <td style="text-align: left">
                                                    {{ en2bn($i++) }} - {{ $product->name }}
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="product_qty[]"
                                                        id="product_qty_{{ $key }}"
                                                        value="{{ $quotation ? $quotation->findquotation($quotation->id, $product->id) : '' }}"
                                                        class="border form-control qty"
                                                        >
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-12"></div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary float-end" id="pointOfSaleBtn"
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

    <script>
        $(document).ready(function(){


            $("#ponitOfSale").on("submit", function(e) {
                e.preventDefault();

                storeData($(this), "pointOfSaleBtn");
            });
        });
    </script>
@endpush

@include('components.select2')

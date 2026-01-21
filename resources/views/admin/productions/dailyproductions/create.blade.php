@extends('admin.layouts.app', ['title' => __('Add New Daily Production')])
@section('panel')
    <form action="{{ route('admin.dailyproduction.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('Add New Daily Production') }}</h5>
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
                </div>

                <div class="row">
                    @foreach ($productswithgroupes as $departmentId => $products)
                        @php
                            $departmentName = optional($products->first()->department)->name;
                        @endphp

                        <div class="col-12">
                            <h5 class="font-weight-bold text-primary mb-2">{{ $departmentName ?: 'No Department' }}</h5>
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
                                                id="product_qty_{{ $key }}" value=""
                                                class="border form-control qty"></td>

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
@push('style')
    <style>
        .form-control:focus {
            border-color: red !important;
        }
    </style>
@endpush
@include('components.select2')

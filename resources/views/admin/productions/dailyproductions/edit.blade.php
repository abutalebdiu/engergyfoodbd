@extends('admin.layouts.app', ['title' => __('Edit Daily Production')])
@section('panel')
    <form action="{{ route('admin.dailyproduction.update', $date) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('Edit Daily Production') }}
                    <a href="{{ route('admin.dailyproduction.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                            class="bi bi-list"></i> @lang('Daily Production List')</a>
                </h5>
            </div>
            <div class="card-body">

                <div class="mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ $date ? $date : Date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                 <div class="row">
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
                                @foreach ($productswithgroupes as $departmentId => $products)
                                    @php
                                        $departmentName = optional($products->first()->department)->name;
                                    @endphp
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-primary text-start">
                                            {{ $departmentName ?: 'No Department' }}
                                        </td>
                                    </tr>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($products as $key => $product)
                                        <tr  style="border-bottom: 2px solid #21b2c1;">
                                            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                            <td>{{ en2bn($i++) }} - </td>
                                            <td style="text-align: left">
                                                {{ $product->name }}
                                            </td>
                                            <td><input type="text" name="product_qty[]"
                                                    id="product_qty_{{ $key }}"  value="{{ $product->dailyproduction($date, $product->id) }}"
                                                    class="border form-control qty"></td>

                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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

@include('components.select2')

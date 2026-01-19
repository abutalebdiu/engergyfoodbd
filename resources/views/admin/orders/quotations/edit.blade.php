@extends('admin.layouts.app', ['title' => __('Edit Quotation')])

@section('panel')
    <form id="editQuotationForm" action="{{ route('admin.quotation.update', $quotation->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">

            <div class="card-header">
                <h5 class="card-title">{{ __('Edit Quotation') }}</h5>
            </div>

            <div class="card-body">
                <div class="mb-4 row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Customer')</label>
                            <div class="input-group">
                                <select name="customer_id" class="form-select select2" id="searchCustomer" required>
                                    @foreach($customers as $customer)
                                        <option {{ $quotation->customer_id ==  $customer->id ? 'selected' : '' }} value="{{ $customer->id }}"> {{ en2bn($customer->uid) }} - {{ $customer->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <input type="date" name="date" value="{{ $quotation->date }}" class="form-control">
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
                                            <th style="width: 70%">@lang('Product')</th>
                                            <th>@lang('Quantity')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($chunk as $key => $product)
                                            <tr style="border-bottom: 2px solid #21b2c1;">
                                                <td style="text-align: left">
                                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        name="product_qty[]"
                                                        id="product_qty_{{ $key }}"
                                                        value="{{ optional($quotation->quotationdetail->where('product_id', $product->id)->first())->qty ?? 0.0 }}"
                                                        class="border form-control qty"
                                                        onkeypress="return validateNumber(event)" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach
                </div>


                <div class="row mt-3">
                    <div class="form-group">
                        <button type="submit" id="submitBtn" class="btn btn-primary float-end">
                            @lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('style')

    <style>
        .form-control:focus {
            border: 3px solid red !important;
        }

        .form-control {
            border-color: #000 !important;
        }
    </style>

@endpush

@push('script')
    <script>
        $("#editQuotationForm").on("submit", function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr("action");
            let formData = new FormData(this);

            $("#manualLoader").fadeIn(200);
            $("#submitBtn").prop("disabled", true).text("সাবমিট হচ্ছে...");

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#manualLoader").fadeOut(200);

                    if(response.status === true){
                        toastr.success(response.message ?? "Quotation updated successfully!");

                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1000);
                    }else{
                        toastr.success(response.message);
                    }

                    $("#submitBtn").prop("disabled", false).text("Submit");
                },
                error: function(xhr) {
                    $("#manualLoader").fadeOut(200);
                    $("#submitBtn").prop("disabled", false).text("Submit");

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(errArr => {
                            errArr.forEach(err => toastr.error(err));
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message ?? "Something went wrong!");
                    }
                }
            });
        });

        // keep your keyboard navigation
        document.addEventListener('DOMContentLoaded', function() {
            const qtyInputs = document.querySelectorAll('input[name="product_qty[]"]');
            qtyInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) nextInput.focus();
                    }
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevInput = qtyInputs[index - 1];
                        if (prevInput) prevInput.focus();
                    }
                });
            });
        });


    </script>
@endpush
@include('components.select2')
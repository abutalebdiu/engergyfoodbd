@extends('admin.layouts.app', ['title' => __('Add New Quotation')])

@section('panel')
    <form id="quotationForm" action="{{ route('admin.quotation.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{ __('New Quotation') }}
                    <a href="{{ route('admin.quotation.index') }}" class="btn btn-outline-primary btn-sm float-end ms-2">
                        <i class="fa fa-list"></i> @lang('Quotation List')
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <div id="alert-message"></div>

                <div class="mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Customer')</label>
                            <div class="input-group">
                                <select name="customer_id" class="form-select select2" id="searchCustomer" required>
                                    <option value="">@lang('Search Customer')</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ en2bn($customer->uid) }} - {{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <input type="date" name="date"
                                value="{{ old('date') ? old('date') : \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}"
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
                                            <th style="width: 70%">@lang('SL No') @lang('Product')</th>
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
                                                        class="border form-control qty"
                                                        value="">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endforeach

                    <div class="col-12 mt-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary float-end" id="submitBtn">
                                @lang('Submit')
                            </button>
                        </div>
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
        $(document).ready(function() {
            $("#quotationForm").on("submit", function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr("action");
                let formData = new FormData(this);

                $("#manualLoader").fadeIn(200);

                $("#submitBtn").prop("disabled", true).text("সাবমিট হচ্ছে...");

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        $("#manualLoader").fadeOut(200); // hide loader

                        if(response.status === true){

                            $("#alert-message").html(
                                `<div class="alert alert-success">${response.message ?? 'Quotation saved successfully!'}</div>`
                            );

                            form[0].reset();

                            $("#submitBtn").prop("disabled", false).text("Submit");

                            toastr.success('Quotation saved successfully!');


                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);

                        } else {
                            toastr.success(response.message)
                        }
                    },
                    error: function(xhr) {
                        $("#manualLoader").fadeOut(200);
                        // hide loader
                        let errors = xhr.responseJSON?.errors;
                        let errorHtml = `<div class="alert alert-danger"><ul>`;

                        if (errors) {
                            Object.values(errors).forEach(errArr => {
                                errArr.forEach(err => errorHtml += `<li>${err}</li>`);
                            });
                        } else {
                            errorHtml += `<li>Something went wrong!</li>`;
                        }
                        errorHtml += `</ul></div>`;
                        $("#alert-message").html(errorHtml);
                        $("#submitBtn").prop("disabled", false).text("Submit");
                        toastr.success('Something went wrong!')
                    }
                });
            });

            // Input navigation (Enter, Up, Down)
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

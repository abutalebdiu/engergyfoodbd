@extends('admin.layouts.app', ['title' => __('Daily Report')])
@section('panel')
    <div>
        <h2>Daily Stock Report</h2>

        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date">Report Start Date</label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ isset($start_date) ? $start_date : '' }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="end_date">Report End Date</label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ isset($end_date) ? $end_date : '' }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="end_date">@lang('Group')</label>

                    <select id="department_id" class="form-select" name="department_id">
                        <option disabled selected>@lang('Select group')</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-products='@json($category->products)'>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 show-products">
                    <label for="product_id">@lang('Product')</label>
                    <select id="product_id" class="form-select" name="product_id">
                        <option disabled selected>@lang('Select product')</option>
                    </select>
                </div>

                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary" id="generateReportBtn">Generate Report</button>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="export" value="1" class="btn btn-success mt-4">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>
                </div>

                <div class="col-md-2">
                    <button type="submit" name="pdf" value="1" class="btn btn-danger mt-4">
                        <i class="fas fa-file-pdf"></i> Export to PDF
                    </button>
                </div>
            </div>
        </form>


        <div class="alert alert-primary">
            Last Stock Report Date: {{ \App\Models\Product\ProductStock::max('date') }}
        </div>

        <div class="alert alert-info">
            Showing stock movements from <span id="startDate"></span> to <span id="endDate"></span>
        </div>


        <div id="loadingIndicator" style="display: none;">
            <div class="loading-spinner"></div>
            <p style="text-align: center;">Generating report, please wait...</p>
        </div>

        <div id="reportResults" class="responsive-table">

        </div>
    </div>
@endsection


@push('style')
    <style>
        .loading-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        #loadingIndicator p {
            text-align: center;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .responsive-table {
            overflow-x: auto;
        }
    </style>
@endpush



@push('script')
    <script>
        $(document).ready(function() {
            function generateReport(start_date = null, end_date = null, department_id = null, product_id = null) {

                $('#loadingIndicator').show();
                $('#reportResults').empty();

                $.ajax({
                    url: "{{ route('admin.reports.daily-reports') }}",
                    type: 'GET',
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        department_id: department_id,
                        product_id: product_id
                    },
                    success: function(response) {
                        $('#loadingIndicator').hide();
                        displayReport(response);
                    },
                    error: function(xhr) {
                        $('#loadingIndicator').hide();
                        alert('Error generating report: ' + xhr.responseText);
                    }
                });
            }

            function displayReport(data) {
                let html = '';

                if (data.status == 'success') {
                    $('#startDate').text(data.start_date);
                    $('#endDate').text(data.end_date);

                    html += data.viewData;

                } else {
                    html += `<p>No data found</p>`;
                }

                $('#reportResults').html(html);
            }

            generateReport();

            $('#generateReportBtn').click(function(e) {
                e.preventDefault();

                let start_date = $('#start_date').val();
                let end_date = $("#end_date").val();
                let department_id = $("#department_id").val();
                let product_id = $("#product_id").val();

                if (start_date === '') {
                    alert('Please add start date');
                    return;
                }

                if (end_date === '') {
                    alert('Please add end date');
                    return;
                }

                let start = new Date(start_date);
                let end = new Date(end_date);

                if (start > end) {
                    alert('Start date must be before End date');
                    return;
                }

               generateReport(start_date, end_date, department_id, product_id);
            });

        });

    </script>

    <script>
        $(document).on("change", "#department_id", function() {
            let products = $(this).find(":selected").data("products") || [];

            let html = `<label for="product_id">@lang('Product')</label>
                        <select id="product_id" class="form-select" name="product_id">`;

            if (products.length === 0) {
                html += `<option disabled selected>@lang('Product not found')</option>`;
            } else {
                html += `<option disabled selected>@lang('Select product')</option>`;
                products.forEach(product => {
                    html += `<option value="${product.id}">${product.name}</option>`;
                });
            }

            html += `</select>`;
            $(".show-products").html(html);
        });
    </script>
@endpush

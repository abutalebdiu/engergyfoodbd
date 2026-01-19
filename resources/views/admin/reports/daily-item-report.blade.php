@extends('admin.layouts.app', ['title' => __('Daily Item Report')])
@section('panel')
    <div>
        <h2>{{ __("Daily Item Stock Report") }}</h2>

        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date">@lang('Report Start Date')</label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ isset($start_date) ? $start_date : '' }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="end_date">@lang('Report End Date')</label>
                    <input type="date" name="start_date" id="end_date"
                        value="{{ isset($end_date) ? $end_date : '' }}" class="form-control">
                </div>


                <div class="col-md-4">
                    <label for="item_category">@lang('Group')</label>

                    <select id="item_category" class="form-select" name="item_category">
                        <option disabled selected>@lang('Select group')</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-items='@json($category->items)'>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 show-items">
                    <label for="item_id">@lang('Item')</label>
                    <select id="item_id" class="form-select" name="item_id">
                        <option disabled selected>@lang('Select item')</option>
                    </select>
                </div>


                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary" id="generateReportBtn">{{ __('Generate Daily Item Report') }}</button>
                </div>

                <div class="col-md-2">
                    <button type="submit" name="export" value="1" class="btn btn-success mt-4">
                        <i class="fas fa-file-excel"></i>{{ __(' Export to Excel') }}
                    </button>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="pdf" value="1" class="btn btn-danger mt-4">
                        <i class="fas fa-file-pdf"></i>{{ __(' Export to PDF') }}
                    </button>
                </div>
            </div>
        </form>


        <div class="alert alert-primary">
            {{ __("Last Stock Report Date") }}: {{ \App\Models\ItemStock::max('date') }}
        </div>

        <div class="alert alert-info">
            {{ __("Showing stock movements from") }} <span id="startDate"></span> {{ __("to") }} <span id="endDate"></span>
        </div>


        <div id="loadingIndicator" style="display: none;">
            <div class="loading-spinner"></div>
            <p style="text-align: center;">{{ __("Generating report, please wait...") }}</p>
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

            function generateReport(start_date = null, end_date = null, item_category = null, item_id = null) {
                $('#loadingIndicator').show();
                $('#reportResults').empty();


                $.ajax({
                    url: "{{ route('admin.reports.daily-item-reports') }}",
                    type: 'GET',
                    data: {
                       start_date: start_date,
                       end_date: end_date,
                       item_category: item_category,
                       item_id: item_id,
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



            function generateAndShowPDF(search_date) {
                $('#exportPdfBtn').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...`);
                $.ajax({
                    url: "{{ route('admin.reports.daily-item-reports') }}",
                    method: 'GET',
                    data: {
                        search_date: search_date,
                        pdf: 1,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    responseType: 'blob',
                    success: function(blob) {
                        $('#exportPdfBtn').html(`<i class="fas fa-file-pdf"></i> Export to PDF`);

                        const url = window.URL.createObjectURL(new Blob([blob], { type: 'application/pdf' }));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `daily_item_report_{{ time() }}.pdf`);
                        document.body.appendChild(link);
                        link.click();
                        link.parentNode.removeChild(link);


                    },
                    error: function(xhr, status, error) {

                        console.log(xhr);

                        alert('Error generating PDF: ' + xhr.responseText);
                        $('#exportPdfBtn').html(`<i class="fas fa-file-pdf"></i> Export to PDF`);
                    }
                });
            }


            generateReport();

            $('#generateReportBtn').click(function(e) {
                e.preventDefault();

                let start_date = $('#start_date').val();
                let end_date = $("#end_date").val();
                let item_category = $("#item_category").val();
                let item_id = $("#item_id").val();

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

                generateReport(start_date, end_date, item_category, item_id);
            });


            $('#exportPdfBtn').click(function(e) {
                e.preventDefault();
                let search_date = $('#search_date').val();
                generateAndShowPDF(search_date);
            });
        });
    </script>


    <script>
        $(document).on("change", "#item_category", function() {
            let items = $(this).find(":selected").data("items") || [];

            let html = `<label for="item_id">@lang('Item')</label>
                        <select id="item_id" class="form-select" name="item_id">`;

            if (items.length === 0) {
                html += `<option disabled selected>@lang('Item not found')</option>`;
            } else {
                html += `<option disabled selected>@lang('Select item')</option>`;
                items.forEach(item => {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });
            }

            html += `</select>`;
            $(".show-items").html(html);
        });
    </script>
@endpush

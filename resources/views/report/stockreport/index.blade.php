@extends('admin.layouts.app', ['title' => 'Stock Reports'])
@section('panel')
    @include('report.layouts.default', [
        'title' => 'Stock Reports',
        'url' => 'admin.reports.stockreport',
        [
            'range_date' => $range_date ? $range_date : null,
        ],
    ])


    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">@lang('SL')</th>
                                        <th style="text-align: left">@lang('Name')</th>
                                        <th>@lang('Sale Price')</th>
                                        <th>@lang('Current Stock Qty')</th>
                                        <th>@lang('Current Value')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalvalue = 0;
                                    @endphp
                                    
                                    @foreach ($productswithdepartments as $departmentId => $products)
                                    @php
                                        $departmentName = optional($products->first()->department)->name;
                                    @endphp
                                    <tr>
                                        <td colspan="5" class="font-weight-bold text-primary text-start">
                                            {{ $departmentName ?: 'No Department' }}
                                        </td>
                                    </tr>
                                    
                                    
                                    @foreach ($products as $item)
                                        @php
                                            $totalvalue += $item->sale_price * $item->getstock($item->id);
                                        @endphp
                                        <tr>
                                            <td>{{ en2bn($loop->iteration) }}</td>
                                            <td style="text-align: left">{{ $item->name }}</td>
                                            <td>{{ en2bn($item->sale_price) }}</td>
                                            <td>{{ en2bn(round($item->getstock($item->id),2)) }}</td>
                                            <td>{{ en2bn(number_format($item->sale_price * round($item->getstock($item->id),2), 2)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tr>
                                    <th colspan="4">@lang('Total')</th>
                                    <th>{{ en2bn(number_format($totalvalue, 2, '.', ',')) }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script></script>
@endpush


@push('style')
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: .375rem !important;
            height: 42px !important;
        }

        .no-focus:focus {
            outline: none;
        }

        .no-border {
            border: none;
        }

        table tr td p {
            font-size: 10px !important;
        }

        p {
            font-size: 11px !important;
        }
    </style>
@endpush

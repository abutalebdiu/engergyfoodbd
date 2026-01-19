@extends('admin.layouts.app', ['title' => __('Products Demand List')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Products Demand List')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('Date')</label>
                            <input type="date" name="date" class="form-control"
                                @if (isset($date)) value="{{ $date }}" @endif>
                        </div>
                    </div>

                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="">Print Format</label>
                            <select name="format" id="format" class="form-control">
                                <option @if (isset($format)) {{ $format == 'A4' ? 'selected' : '' }} @endif
                                    value="A4">A4</option>
                                <option @if (isset($format)) {{ $format == 'A1' ? 'selected' : '' }} @endif
                                    value="A1">A1</option>
                                <option @if (isset($format)) {{ $format == 'A2' ? 'selected' : '' }} @endif
                                    value="A2">A2</option>
                                <option @if (isset($format)) {{ $format == 'A3' ? 'selected' : '' }} @endif
                                    value="A3">A3</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="">Page Orientation</label>
                            <select name="orientation" id="orientation" class="form-control">
                                <option @if (isset($orientation)) {{ $orientation == 'P' ? 'selected' : '' }} @endif
                                    value="P">Portrait</option>
                                <option @if (isset($orientation)) {{ $orientation == 'L' ? 'selected' : '' }} @endif
                                    value="L">Landscape</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="">Report Type</label>
                            <select name="type" id="type" class="form-control">
                                <option @if (isset($type)) {{ $type == 'WC' ? 'selected' : '' }} @endif
                                    value="WC">@lang('With Customer')</option>
                                <option @if (isset($type)) {{ $type == 'WOC' ? 'selected' : '' }} @endif
                                    value="WOC">@lang('Without Customer')</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button>
                    </div>
                </div>
            </form>

            @if ($searching == 'Yes')
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('SL No')</th>
                                        <th>@lang('Product')</th>
                                        @if ($type == 'WC')
                                            @foreach ($customers as $customer)
                                                <th>{{ $customer->name }}</th>
                                            @endforeach
                                        @endif
                                        <th>@lang('Total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = 0;
                                        $customerTotals = array_fill_keys($customers->pluck('id')->toArray(), 0);
                                    @endphp

                                    @foreach ($products_by_department as $departmentId => $productsInDepartment)
                                        @php
                                            $departmentName =
                                                $productsInDepartment->first()->first()->department_name ??
                                                'No Department';
                                        @endphp
                                        <tr class="table-secondary">
                                            <td colspan="{{ 3 + count($customers) }}" style="text-align: left">
                                                <strong>{{ $departmentName }}</strong>
                                            </td>
                                        </tr>

                                        @foreach ($productsInDepartment as $productId => $productGroup)
                                            @php $productTotal = $productGroup->sum('total_qty'); @endphp
                                            <tr>
                                                <td>{{ en2bn($loop->iteration) }}</td>
                                                <td style="text-align: left">{{ $productGroup->first()->name }}</td>

                                                @foreach ($customers as $customer)
                                                    @php
                                                        $customerOrder = $productGroup
                                                            ->where('customer_id', $customer->id)
                                                            ->first();
                                                        $qty = $customerOrder ? $customerOrder->total_qty : 0;
                                                        $customerTotals[$customer->id] += $qty;
                                                    @endphp
                                                    @if ($type == 'WC')
                                                        <td>{{ en2bn($qty) }}</td>
                                                    @endif
                                                @endforeach

                                                <td>{{ en2bn($productTotal) }}</td>
                                                @php $grandTotal += $productTotal; @endphp
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>@lang('Total')</th>
                                        @foreach ($customers as $customer)
                                            @if ($type == 'WC')
                                                <th>{{ en2bn($customerTotals[$customer->id]) }}</th>
                                            @endif
                                        @endforeach
                                        <th>{{ en2bn(number_format($grandTotal, 0, '.', ',')) }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@extends('admin.layouts.app', ['title' => __('Balance Sheets')])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                @lang('Balance Sheets')
            </h6>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                @if (isset($start_date)) value="{{ $start_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                @if (isset($end_date)) value="{{ $end_date }}" @endif>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" name="search" class="btn btn-primary mt-4"><i class="bi bi-search"></i>
                            @lang('Search')</button>
                        <button type="submit" name="pdf" class="btn btn-primary mt-4"><i class="bi bi-download"></i>
                            @lang('PDF')</button>
                        {{-- <button type="submit" name="excel" class="btn btn-primary  mt-4"><i class="bi bi-download"></i>
                            @lang('Excel')</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Balanche Sheets</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan="3">Assets</th>
                        <th colspan="3">Liabilities</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td style="text-align:left;padding-left:10px">Office Assets</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetsamount, 2)) }}</td>
                        <td>1</td>
                        <td style="text-align:left;padding-left:10px">Investment Equity</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($liabilities, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align:left;padding-left:10px">Asset Expenses</td>
                        <td  style="text-align: right;padding-right:10px">{{ en2bn(number_format($assetexpenses, 2)) }}</td>
                        <td>2</td>
                        <td style="text-align:left;padding-left:10px">Office Payable (Salary)</td>
                        <td  style="text-align: right;padding-right:10px">{{ en2bn(number_format($salarypayable, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align:left;padding-left:10px">Salary Advanced</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($salaryadvances, 2)) }}</td>
                        <td>3</td>
                        <td style="text-align:left;padding-left:10px">Supplier Payable</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($payables, 2)) }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px">Items Stock Value</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($itemstockamount, 2)) }}</td>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px"> Monthly Expenses </td>
                        <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($monthlyexpense, 2)) }} </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px">Products Stock Value</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($prductstockamount, 2)) }}</td>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px">  কারখানা ভাড়া </td>
                        <td style="text-align: right;padding-right:10px">  {{ en2bn(number_format($factoryrent, 2)) }} </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px">Employee Loan Receivable</td>
                        <td style="text-align: right;padding-right:10px">{{ en2bn(number_format($employeeloan, 2)) }}</td>
                        <td>4</td>
                        <td style="text-align:left;padding-left:10px"> জামানত </td>
                        <td style="text-align: right;padding-right:10px"> {{ en2bn(number_format($jamanot, 2)) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align:left;padding-left:10px">Total Assets</th>
                        <th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalassets, 2)) }}</th>
                        <th colspan="2">Total Liabilities</th>
                        <th style="text-align: right;padding-right:10px">{{ en2bn(number_format($totalliabilities, 2)) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5"> Different</th>
                        <th>{{ en2bn(number_format($differentvalue, 2)) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

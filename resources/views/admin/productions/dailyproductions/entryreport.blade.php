@extends('admin.layouts.app', ['title' => __('Productions Entry Report')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('Productions Entry Report') }}</h5>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('Start Date')</label>
                            <input type="date" class="form-control" name="start_date"
                                value="{{ $start_date ? $start_date : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">@lang('End Date')</label>
                            <input type="date" class="form-control" name="end_date"
                                value="{{ $end_date ? $end_date : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="d-flex">
                            <div class="form-group">
                                <button class="btn btn-primary mt-4" name="search" value="search"> <i class="fa fa-search"></i>
                                    @lang('Search')
                                </button>
                            </div>
                            <div class="form-group ms-2">
                                <button class="btn btn-info mt-4" name="pdf" value="pdf"> <i class="fa fa-print"></i>
                                    @lang('PDF')
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>


            @if ($searching == 'Yes')
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('Sl')</th>
                                        <th>@lang('Product Name')</th>
                                        <th>@lang('Weight')</th>
                                        @foreach($dates as $date)
                                            <th>{{ $date }}</th>
                                        @endforeach
                                        <th>@lang('Total Qty')</th> <!-- Total per product -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        @php
                                            $totalPerProduct = 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="text-align:left">{{ $product->name }}</td>
                                            <td>{{ $product->weight }}</td>
                                            @foreach($dates as $date)
                                                @php
                                                    $qty = isset($productions[$product->id]) 
                                                        ? $productions[$product->id]->firstWhere('date', $date)?->total_qty ?? 0 
                                                        : 0;
                                                    $totalPerProduct += $qty;
                                                @endphp
                                                <td>{{ $qty }}</td>
                                            @endforeach
                                            <td><strong>{{ $totalPerProduct }}</strong></td> <!-- Right-side total qty -->
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">@lang('Total')</th>
                                        @foreach($dates as $date)
                                            <th>{{ $dateWiseSum[$date] ?? 0 }}</th>
                                        @endforeach
                                        <th>
                                            <strong>{{ number_format(array_sum($dateWiseSum),0) }}</strong>
                                        </th> <!-- Grand total of all quantities -->
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

@include('components.select2')

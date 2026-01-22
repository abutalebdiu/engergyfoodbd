@extends('admin.layouts.app', ['title' => 'Product Stock Settlement List'])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                Product Stock Settlement List
            </h6>

            <div>
                <a href="{{ route('admin.productstock.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-plus"></i> Add New Settlement</a>
                <a href="{{ route('admin.productstock.create') }}?type=pdf"
                    class="btn btn-outline-primary btn-sm float-end ms-2"> <i class="fa fa-download"></i> Download Stock</a>
            </div>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row row-cols-1 row-cols-md-3 mb-3 g-2">
                    <div class="col">
                        <div class="form-group">
                            <select name="month_id" id="month_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $item)
                                    <option {{ request()->month_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <select name="year" id="year" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($years as $year)
                                    <option {{ request()->year == $year->name ? 'selected' : '' }}
                                        value="{{ $year->name }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <button type="submit" name="search" class="btn btn-primary"><i class="fa fa-search"></i>
                                @lang('Search')</button>
                            <button type="submit" name="pdf" class="btn btn-info"> PDF</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('Last Month Stock')</th>
                            <th>@lang('Production')</th>
                            <th>@lang('Sales')</th>
                            <th>@lang('Returns')</th>
                            <th>@lang('Stock Damage')</th>
                            <th>@lang('Customer Damage')</th>
                            <th>@lang('Get Stock')</th>
                            <th>@lang('Get Stock Value')</th>
                            <th>@lang('Physical QTY')</th>
                            <th>@lang('Physical Stock Value')</th>
                            <th>@lang('Settlement QTY')</th>
                            <th>@lang('Settlement Value')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $phystockvalue = 0;
                            $getstockvalue = 0;
                        @endphp


                         @php
                            $i = ($productstocks->currentPage() - 1) * $productstocks->perPage() + 1;
                        @endphp

                        @forelse($productstocks as $item)
                        
                            @php
                                $phystockvalue += $item->physical_stock * $item->product->sale_price;
                                $getstockvalue += $item->current_stock * $item->product->sale_price;
                            @endphp
                          
                            <tr>
                                <td> {{ en2bn($i++) }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }} </td>
                                <td style="text-align: left"> {{ optional($item->month)->name }}-{{ $item->year }}</td>
                                <td style="text-align: left"> {{ optional($item->product)->name }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->last_month_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->production, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->sales, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->order_return, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->damage, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->customer_damage, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->current_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->current_stock * $item->product->sale_price, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->physical_stock, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->physical_stock * $item->product->sale_price, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->qty, 2, '.', ',')) }}</td>
                                <td style="text-align: right;padding-right:10px">
                                    {{ en2bn(number_format($item->total_value, 2, '.', ',')) }}</td>
                                <td>
                                    {!! entry_info($item) !!}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.productstock.edit', $item->id) }}"> <i class="fa fa-edit"></i> Edit</a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.productstock.destroy', $item->id) }}">
                                                    <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">@lang('Total')</th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('last_month_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('production'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('sales'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('order_return'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('damage'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('customer_damage'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('current_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($getstockvalue, 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('physical_stock'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($phystockvalue, 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('qty'), 2, '.', ',')) }} </th>
                            <th style="text-align: right;padding-right:10px">
                                {{ en2bn(number_format($productstocks->sum('total_value'), 2, '.', ',')) }} </th>
                            <th colspan="2"> </th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>

            <!-- pagination start -->
            <div class="d-flex justify-content-start mt-3">
                {!! $productstocks->links() !!}
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

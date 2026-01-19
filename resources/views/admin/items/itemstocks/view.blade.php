@extends('admin.layouts.app', ['title' => 'Item Stock Settlement List'])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                Item Stock Settlement List
            </h6>

            <div>
                <a href="{{ route('admin.itemtstock.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-plus"></i> Add New Settlement</a>
                <a href="{{ route('admin.itemtstock.create') }}?type=pdf"
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
                                @foreach ($months as $month)
                                    <option {{ request()->month_id == $month->id ? "selected" : "" }} value="{{ $month->id }}">{{ $month->name }}</option>
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
                            <th>@lang('Item Name')</th>
                            <th>@lang('Month')</th>
                            <th>@lang('Last Month Stock')</th>
                            <th>@lang('Purchase')</th>
                            <th>@lang('Make Production')</th>
                            <th>@lang('Production Loss')</th>
                            <th>@lang('Get Stock')</th>
                            <th>@lang('Get Stock Value')</th>
                            <th>@lang('Physical QTY')</th>
                            <th>@lang('Physical Value')</th>
                            <th>@lang('Settlement QTY')</th>
                            <th>@lang('Settlement Value')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php
                            $grandTotalStock = 0;
                            $grandTotalValue = 0;
                            
                            $grandTotalPhyStock = 0;
                            $grandTotalPhyValue = 0;
                            
                            $grandTotalSettStock = 0;
                            $grandTotalSettValue = 0;
                        @endphp
                        @forelse($itemstocks as $category_id => $items)
                            <tr class="bg-secondary">
                                <td colspan="15" class="text-start  text-white"><strong>{{ optional($items->first()->item->category)->name }}</strong></td>
                            </tr>
                        
                           @php
                                $categoryTotalStock = $items->sum('current_stock');
                                $categoryTotalValue = $items->sum(fn($item) => $item->current_stock * $item->item->price);
                                
                                $grandTotalStock += $categoryTotalStock;
                                $grandTotalValue += $categoryTotalValue;
                                
                                
                                $categoryTotalPhyStock = $items->sum('physical_stock');
                                $categoryTotalPhyValue = $items->sum(fn($item) => $item->physical_stock * $item->item->price);
                                
                                $grandTotalPhyStock += $categoryTotalPhyStock;
                                $grandTotalPhyValue += $categoryTotalPhyValue;
                                
                                $categoryTotalSettStock = $items->sum('qty');
                                $categoryTotalSettValue = $items->sum(fn($item) => $item->qty * $item->item->price);
                                
                                $grandTotalSettStock += $categoryTotalSettStock;
                                $grandTotalSettValue += $categoryTotalSettValue;
                            @endphp
                        
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-wrap">{{ optional($item->item)->name }}</td>
                                    <td>{{ optional($item->month)->name }}</td>
                                    <td>{{ number_format($item->last_month_stock, 2) }}</td>
                                    <td>{{ number_format($item->purchase, 2) }}</td>
                                    <td>{{ number_format($item->make_production, 2) }}</td>
                                    <td>{{ number_format($item->production_loss, 2) }}</td>
                                    <td>{{ number_format($item->current_stock, 2) }}</td>
                                    <td>{{ number_format($item->current_stock * $item->item->price, 2) }}</td>
                                    <td>{{ number_format($item->physical_stock, 2) }}</td>
                                    <td>{{ number_format($item->physical_stock * $item->item->price, 2) }}</td>
                                    <td>{{ number_format($item->qty, 2) }}</td>
                                    <td>{{ number_format($item->total_value, 2) }}</td>
                                    <td>{{ optional($item->entryuser)->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button data-bs-toggle="dropdown">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.itemtstock.edit', $item->id) }}"> <i class="fa fa-edit"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                        data-question="@lang('Are you sure to remove this data from this list?')"
                                                        data-action="{{ route('admin.itemtstock.destroy', $item->id) }}">
                                                        <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach 
                        
                            {{-- Category Total --}}
                            <tr class="bg-light">
                                <td colspan="7" class="text-end"><strong>Category Total:</strong></td>
                                <td><strong>{{ number_format($categoryTotalStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalValue, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalPhyStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalPhyValue, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalSettStock, 2) }}</strong></td>
                                <td><strong>{{ number_format($categoryTotalSettValue, 2) }}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="14">{{ __('No data available') }}</td>
                            </tr>
                        @endforelse
                        
                        {{-- Grand Total --}}
                        <tfoot>
                            <tr class="bg-dark text-white">
                                <th colspan="7" class="text-end"><strong>Grand Total:</strong></th>
                                <th><strong>{{ number_format($grandTotalStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalValue, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalPhyStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalPhyValue, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalSettStock, 2) }}</strong></th>
                                <th><strong>{{ number_format($grandTotalSettValue, 2) }}</strong></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot> 
                </table>
            </div>

        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

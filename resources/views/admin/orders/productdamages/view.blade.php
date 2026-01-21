@extends('admin.layouts.app', ['title' => 'Damage Product List'])
@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">
                Damage Product List
            </h6>

            <div>
                <a href="{{ route('admin.productdamage.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="fa fa-plus"></i> Add Damage Product</a>
            </div>
        </div>
        <div class="card-body">
            <form action="">
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <select name="product_id" id="product_id" class="form-select select2">
                            <option value="">@lang('Search product')</option>
                            @foreach ($products as $product)
                                <option
                                    @if (isset($product_id)) {{ $product_id == $product->id ? 'selected' : '' }} @endif
                                    value="{{ $product->id }}"> {{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="start_date"
                            @if (isset($start_date)) value="{{ $start_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="end_date"
                            @if (isset($end_date)) value="{{ $end_date }}" @else value="{{ Date('Y-m-d') }}" @endif
                            class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" name="search" class="btn btn-primary btn-sm"><i class="bi bi-search"></i>
                            Search</button>
                        <button type="submit" name="pdf" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            PDF</button>
                        <button type="submit" name="excel" class="btn btn-primary btn-sm"><i class="bi bi-download"></i>
                            Excel</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Product')</th>
                            <th>@lang('QTY')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Reason')</th>
                            <th>@lang('Entry By')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php  $totalamount = 0; @endphp
                        @forelse($productdamages as $item)
                            @php  $totalamount += ($item->qty * $item->product->sale_price); @endphp
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                <td> {{ optional($item->product)->name }}</td>
                                <td>{{ en2bn($item->qty) }}</td>
                                <td>{{ en2bn(number_format($item->qty * $item->product->sale_price), 2) }}</td>
                                <td>{{ $item->reason }}</td>
                                <td> 
                                    {{ optional($item->entryuser)->name }} 
                                    </br>
                                    {{ optional($item->entryuser)->updated_at->format('d-m-Y h:i A') }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.productdamage.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                    data-question="@lang('Are you sure to remove this data from this list?')"
                                                    data-action="{{ route('admin.productdamage.destroy', $item->id) }}">
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
                            <th>{{ en2bn(number_format($totalamount)) }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>


    <x-destroy-confirmation-modal />
@endsection

@include('components.select2')

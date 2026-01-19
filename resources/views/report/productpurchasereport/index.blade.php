@extends('admin.layouts.app', ['title' => 'Product Purchase Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Product Purchase Reports', 'url' => 'admin.reports.productpurchasereport', [
            'range_date' => $range_date ? $range_date : null,
        ]
    ])

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.productpurchasereport') }}" method="GET">
                        <div class="row">

                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif

                            @if(request('filter') === 'custom_range')
                                <input type="hidden" name="range" value="{{ request('range') }}">
                            @endif

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="brand_id" class="form-label">@lang('Purchase ID'):</label>
                                    <input type="text" name="purchase_id" id="purchase_id" class="form-control" value="{{ request('purchase_id') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mt-2 form-group">
                                    <button type="submit" class="mt-4 btn btn-primary">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Brand')</th>
                                    <th>@lang('Sku/Code')</th>
                                    <th>@lang('Supplier')</th>
                                    <th>@lang('Reference')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Quantity Adjusted')</th>
                                    <th>@lang('Quantity Price')</th>
                                    <th>@lang('Subtotal')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->brand }}</td>
                                    <td>{{ $item->code }}/{{ $item->pid }}</td>
                                    <td>{{ $item->supplier_name }}</td>
                                    <td>{{ $item->reference_invoice_no }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ number_format($item->quantity, 3) }}</td>
                                    <td>{{ number_format($item->price, 3) }}</td>
                                    <td>{{ number_format($item->totalamount, 3) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    @if(count($purchases) > 0)
                        <div class="d-flex justify-content-center">
                            {{ $purchases->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')


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

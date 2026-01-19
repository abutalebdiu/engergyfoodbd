@extends('admin.layouts.app', ['title' => 'Product Sales Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Product Sales Reports', 'url' => 'admin.reports.productsalesreport', [
            'range_date' => $range_date ? $range_date : null,
        ]
    ])

<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.productsalesreport') }}" method="GET">
                        <div class="row">

                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif

                            @if(request('filter') === 'custom_range')
                                <input type="hidden" name="range" value="{{ request('range') }}">
                            @endif

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_id" class="form-label">@lang('Order ID'):</label>
                                    <input type="text" name="order_id" id="order_id" class="form-control" value="{{ request('order_id') }}">
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
                                    <th>@lang('Sku/Code')</th>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Contact')</th>
                                    <th>@lang('Reference')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Quantity')</th>
                                    <th>@lang('Quantity Price')</th>                                   
                                    
                                    <th>@lang('Total')</th>
                                    <th>@lang('Payment Method')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($orders))
                                    @foreach ($orders as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->oid }}</td>
                                        <td>{{ $item->customer_name }}</td>
                                        <td>{{ $item->contact }}</td>
                                        <td>{{ optional($item->reference)->name }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ number_format($item->quantity, 3) }}</td>
                                        <td>{{ number_format($item->price, 3) }}</td>                                        
                                        
                                        <td>{{ number_format($item->totalamount, 3) }}</td>
                                        <td>{{ $item->payment_method }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    @if(count($orders) > 0)
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')

<script>
    $(document).ready(function() {
        $('.type').on('change', function() {
            $type = $(this).val();
            $url = $(this).closest('form').attr('action');

            if ($type == 'customer') {
                $url = $url + "?type=customer";
            } else if ($type == 'supplier') {
                $url = $url + "?type=supplier";
            } else {
                $url = $url + "?type=all";
            }

            $(this).closest('form').attr('action', $url);
        });


        $('.contact').on('change', function() {
            $contact = $(this).val();
            $url = $(this).closest('form').attr('action');

            $url = $url +"?contact=" + $contact;

            $(this).closest('form').attr('action', $url);
        });
    });
</script>


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

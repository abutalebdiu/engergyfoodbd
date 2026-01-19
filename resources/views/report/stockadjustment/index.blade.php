@extends('admin.layouts.app', ['title' => 'Stock Adjustment Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Stock Adjustment Reports', 'url' => 'admin.reports.stockadjustment', [
            'range_date' => $range_date ? $range_date : null,
        ]
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
                                        <th>@lang('Name')</th>                                       
                                        <th>@lang('Quantity Adjust')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>                                        
                                        <td>{{ $item->getstock($item->id) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


 
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

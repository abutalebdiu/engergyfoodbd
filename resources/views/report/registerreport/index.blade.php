@extends('admin.layouts.app', ['title' => 'Register Reports'])
@section('panel')

@include('report.layouts.default',
    ['title' => 'Register Reports', 'url' => 'admin.reports.registerreport', [
            'range_date' => $range_date ? $range_date : null,
        ]
    ])


<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.registerreport') }}" method="GET">
                        <div class="row">

                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif

                            @if(request('filter') === 'custom_range')
                                <input type="hidden" name="range" value="{{ request('range') }}">
                            @endif

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id" class="form-label">@lang('Users'):</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option {{ request('user_id') == 'all' ? 'selected' : '' }} value="all">@lang('All')</option>
                                        @foreach ($users as $user)
                                            <option {{ request('user_id') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status" class="form-label">@lang('Status'):</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ request('status') == 'all' ? 'selected' : '' }} value="all">@lang('All')</option>
                                        <option {{ request('status') == '1' ? 'selected' : '' }} value="1">@lang('Open')</option>
                                        <option {{ request('status') == '0' ? 'selected' : '' }} value="0">@lang('Close')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="page_size" class="form-label">@lang('Pages'):</label>
                                    <select name="page_size" id="page_size" class="form-control">
                                        <option {{ request('page_size') == '10' ? 'selected' : '' }} value="10">10</option>
                                        <option {{ request('page_size') == '20' ? 'selected' : '' }} value="20">20</option>
                                        <option {{ request('page_size') == '30' ? 'selected' : '' }} value="30">30</option>
                                        <option {{ request('page_size') == '50' ? 'selected' : '' }} value="50">50</option>
                                        <option {{ request('page_size') == '100' ? 'selected' : '' }} value="100">100</option>
                                    </select>
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
                                    <th>Opening</th>
                                    <th>Closing</th>
                                    <th>Contact</th>
                                    <th>Total Purcheses</th>
                                    <th>Total Purcheses Return</th>
                                    <th>Total Sale</th>
                                    <th>Total Sale Return</th>
                                    <th>Opening Due Balance</th>
                                    <th>Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registers as $contact)
                                <tr>
                                    <td>{{ $contact->register_open_date_time }}</td>
                                    <td>{{ $contact->register_closed_date_time }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ number_format($contact->total_purchase, 3) }}</td>
                                    <td>{{ number_format($contact->total_sale, 3) }}</td>
                                    <td>{{ number_format($contact->total_purchase_return, 3) }}</td>
                                    <td>{{ number_format($contact->total_sale_return, 3) }}</td>
                                    <td>{{ number_format($contact->opening_balance, 3) }}</td>
                                    <td>{{ number_format($contact->total_due, 3) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    @if(count($registers) > 0)
                        <div class="d-flex justify-content-center">
                            {{ $registers->links() }}
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

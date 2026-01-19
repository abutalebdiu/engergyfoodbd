@extends('admin.layouts.app', ['title' => 'Day Closed Account Statement'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Day Closed Account Statement 
                <a href="{{ route('admin.account.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="fa fa-list"></i> Account List</a>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Opening Balance')</th>
                            <th>@lang('Closed balance')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyreports as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ en2bn(Date('d-m-Y',strtotime($item->date))) }} </td>
                                <td>{{ en2bn(number_format($item->opening_balance, 2, '.', ',')) }}</td>
                                <td>{{ en2bn(number_format($item->account_balance, 2, '.', ',')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

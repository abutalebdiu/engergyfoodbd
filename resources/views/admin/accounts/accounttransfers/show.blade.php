@extends('admin.layouts.app', ['title' => 'Account Statement'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Account statement | Available Balance - {{ number_format($account->balance($account->id)) }} BDT
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
                            <th>@lang('Transaction No')</th>
                            <th>@lang('Module')</th>
                            <th>@lang('Module Invoice No')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Mother Account')</th>
                            <th>@lang('Buyer Account')</th>
                            <th>@lang('Credit Amount')</th>
                            <th>@lang('Debit Amount')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Client/Party')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account->transactionhistories as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->txt_no }} </td>
                                <td> {{ optional($item->moduletype)->name }}</td>
                                <td> {{ $item->invoice_no }}</td>
                                <td> {{ optional($item->paymentmethod)->name }}</td>
                                <td> {{ optional($item->account)->title }}</td>
                                <td> {{ optional($item->buyeraccount)->title }}</td>
                                <td>
                                    @if ($item->cdf_type == 'credit')
                                        {{ number_format($item->amount) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->cdf_type == 'debit')
                                        {{ number_format($item->amount) }}
                                    @endif
                                </td>
                                <td> {{ $item->cdf_type }}</td>
                                <td> {{ $item->note }}</td>
                                <td>
                                    @if ($item->client_id)
                                        {{ optional($item->client)->name }} ({{ optional($item->client)->company_name }})
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7"></th>
                            <th>{{ number_format($account->transactionhistories->where('cdf_type','credit')->sum('amount')) }}</th>
                            <th>{{ number_format($account->transactionhistories->where('cdf_type','debit')->sum('amount')) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

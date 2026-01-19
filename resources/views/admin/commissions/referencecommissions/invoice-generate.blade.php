@extends('admin.layouts.app', ['title' => 'Commission invoice generate for Customer'])
@section('panel')

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">@lang('Commission invoice generate for') <a href="#">{{ $user->name }}</a></h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.commission.getCommissionOrder', ['user_id' => $user->id]) }}" method="GET">
                <div class="form form-inline my-3">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('Start Date')</label>
                                <input class="form-control" type="date" name="start_date"
                                    value="{{ request()->start_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label text-capitalize">@lang('End Date')</label>
                                <input class="form-control" type="date" name="end_date"
                                    value="{{ request()->end_date ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </form>
            <form action="{{ route('admin.commission.getCommissionStore', ['user_id' => $user->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="pb-3 col-12 col-md-12">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Grand Total')</th>
                                    <th>@lang('Return Amount')</th>
                                    <th>@lang('Net Amount')</th>
                                    <th>@lang('Paid Amount')</th>
                                    <th>@lang('Due Total')</th>
                                    <th>@lang('Commission Status')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Due Amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($orders))
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                            <td class="text-capitalize">
                                                <label class="form-label text-capitalize">#{{ $order->oid }}</label>
                                                <input type="hidden" name="od_id[]" value="{{ $order->id }}">
                                            </td>
                                            <td> {{ $order->date }} </td>
                                            <td> {{ $order->totalamount }}</td>
                                            <td> {{ $order->return_amount }}</td>
                                            <td> {{ $order->net_amount }}</td>
                                            <td> {{ $order->paid_amount }}</td>
                                            <td> {{ $order->net_amount - $order->paid_amount }}</td>
                                            <td>
                                                {{ $order->commission_status }}
                                            </td>
                                            <td> {{ $order->commission_amount ?? 0 }}</td>
                                            <td>
                                                    @if($order->commission_status == "Unpaid")
                                                        {{ $order->due_amount - $order->commission_amount  }}
                                                     <input type="hidden" value="{{ $order->due_amount - $order->commission_amount }}" name="amount[]">
                                                    @else
                                                    <input type="hidden" value="{{ $order->due_amount }}" name="amount[]">
                                                   @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <a href="#" class="btn btn-outline-info float-start">@lang('Back')</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    </form>
@endsection


@push('style')
    <style>
        .table tr td.size-50 {
            width: 50%;
        }
    </style>
@endpush

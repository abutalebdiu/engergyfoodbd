@extends('admin.layouts.app', ['title' => 'Invoice'])

@section('panel')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h6 class="mb-0 text-capitalize">@lang('Invoice') #{{ $invoice->invoice_id ?? $invoice->id }}
                @if($invoice->payment_status == "Unpaid")
                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                @elseif($invoice->payment_status == "Paid")
                <span class="badge bg-success">{{ __('Paid') }}</span>
                @elseif($invoice->payment_status == "Partial")
                <span class="badge bg-warning">{{ __('Partial') }}</span>
                @endif
            </h6>
            <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                data-bs-target="#customer_payment">
                <i class="fa fa-money-bill"></i> @lang('Commission Payment')
            </a>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="customer_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.commission.getCommissionPayment', ['id' => $invoice->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Customer Commission Payment')
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="" class="form-label">@lang('Customer')
                                            {{$invoice->customer?->name}}</label>
                                        <input type="hidden" name="customer_id" value="{{ $invoice->customer?->id }}">
                                        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Payment Method')</label>
                                        <select name="payment_method_id" id="payment_method_id"
                                            class="form-control payment_method_id" required>
                                            <option value="">@lang('Select Method')</option>
                                            @foreach ($payment_methods as $method)
                                            <option data-accounts="{{ $method->accounts }}" value="{{ $method->id }}"
                                                @selected(old('payment_method_id')==$method->id)>
                                                {{ $method->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label class="form-label">@lang('Account')</label>
                                        <select name="account_id" id="account_id" class="form-control account_id"
                                            required>
                                            <option value="">@lang('Select Account')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Due Amount')</label>
                                        <input type="text" name="total_due"
                                            value="{{ $due = $invoice->amount - ($invoice->paid_amount + $invoice->less_amount) ?? 0 }}"
                                            class="form-control text-danger total_due" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Pay Amount')</label>
                                        <input type="text" name="amount" class="form-control pay_amount">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Less Amount')</label>
                                        <input type="text" name="less_amount" class="form-control less_amount">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Date')</label>
                                        <input type="text" value="{{ date('Y-m-d') }}" name="date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('Close')</button>
                                @if($total_due <= $due)
                                    <button type="submit" class="btn btn-primary" name="type" value="withdraw">@lang('Withdraw')</button>
                                @else
                                    <button type="submit" class="btn btn-danger" name="type" value="make_payment" onsubmit="return confirm('Are you sure?' . $due . ' will be deducted.)">
                                     @lang('Make Payment')
                                    </button>

                                    <button type="submit" class="btn btn-primary" name="type" value="withdraw">@lang('Withdraw')</button>
                                @endif
                            
                        </div>
                    </div>
                </form>

                @push('script')
                <script>
                    $('[name=payment_method_id]').on('change', function() {
                      var accounts = $(this).find('option:selected').data('accounts');
                      var option = '<option value="">@lang('Select Account')</option>';
                      $.each(accounts, function(index, value) {
                          option += "<option value='" + value.id + "'>" + value.title + "</option>";
                      });
                      $('select[name=account_id]').html(option);
                  }).change();
                </script>
                @endpush
            </div>
        </div>

    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <h6 class="pb-2 mb-3 border-bottom">@lang('Customer Information')</h6>
                <p>
                    @lang('Name'): {{ $invoice->customer?->name }} <br>
                    @lang('Mobile'): {{ $invoice->customer?->mobile }} <br>
                    @if ($invoice->customer?->email)
                    @lang('Email') : {{ $invoice->customer?->email }} <br>
                    @endif
                    @if ($invoice->customer?->address)
                    @lang('Address') : {{ $invoice->customer?->address }}
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-6">
                <h6 class="pb-2 mb-3 border-bottom">@lang('Customer Commission Invoice Information')</h6>
                <p>
                    @lang('Total Commission') : {{ $invoice->amount ?? 0 }}<br>
                    @lang('Total Paid') : {{ $invoice->paid_amount ?? 0 }}<br>
                    @lang('Total Due') : {{ $invoice->amount - ($invoice->paid_amount + $invoice->less_amount) ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 text-capitalize">@lang('Commission Order')</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>@lang('Order ID')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Commission')</th>
                        <th>@lang('Total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td class="text-capitalize">#{{ $order->oid ?? 'N/A' }}</td>
                        <td class="text-capitalize">{{ $order->date ?? 'N/A' }}</td>
                        <td class="text-capitalize">{{ $order->commission_amount ?? 0 }}</td>
                        <td class="text-capitalize">{{ $order->commission_amount ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link active" aria-current="page" id="v-pills-home-tab"
                    data-bs-toggle="pill" data-bs-target="#v-pills-home" role="tab" aria-controls="v-pills-home"
                    aria-selected="true">@lang('Payment History')</a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>@lang('SL')</th>
                                <th>@lang('Tnx No')</th>
                                <th>@lang('Invoice No')</th>
                                <th>@lang('Customer Name')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Less Amount')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Account')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Entry By')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoicepayments as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tnx_no ?? 'N/A' }}</td>
                                <td>{{ $item->invoice?->invoice_id ?? 'N/A' }}</td>
                                <td>{{ $item->customer?->name ?? 'N/A' }}</td>
                                <td>{{ number_format($item->amount) }}</td>
                                <td>{{ number_format($item->less_amount) }}</td>
                                <td>{{ $item->paymentmethod?->name ?? 'N/A' }}</td>
                                <td>{{ $item->account?->title ?? 'N/A' }}</td>
                                <td>{{ $item->date ?? 'N/A' }}</td>
                                <td>{{ $item->entryuser?->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="btn btn-{{ statusButton($item->status) }} btn-sm">
                                        {{ $item->status ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('.pay_amount').on('keyup', function() {
            // You can implement your logic for the amount calculations here
        });

        var due = $('.total_due').val();

        var orderDue = "{{ $total_due }}";

        if(due == 0){
            $('.less_amount, .pay_amount').attr('disabled', true);
        }
    });
</script>
@endpush

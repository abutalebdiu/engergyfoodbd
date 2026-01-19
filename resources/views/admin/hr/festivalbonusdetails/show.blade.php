@extends('admin.layouts.app', ['title' => 'Festival Bonus Payment'])
@section('panel')
     
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Festival Bonus Payment
            
                <button type="button" class="btn btn-success btn-sm float-end ms-2" data-bs-toggle="modal" data-bs-target="#bonus_payment"><i class="fa fa-money-bill"></i>  Make Payment </button>
                <a href="{{ route('admin.festivalbonusdetail.index') }}" class="btn btn-primary btn-sm float-end"> <i class="fa fa-list"></i> Back to List</a>
            </h4>
            
             <div class="modal fade" id="bonus_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.festivalbonuspayment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Bonus Payment')</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">
                                                @lang('Employee'):
                                                {{ $festivalbonusdetail->employee?->name }}
                                            </label>
                                            <input type="hidden" name="employee_id" value="{{ $festivalbonusdetail->employee_id }}">
                                            <input type="hidden" name="festival_bonus_detail_id" value="{{ $festivalbonusdetail->id }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Payment Method')</label>
                                            <select name="payment_method_id" id="payment_method_id"
                                                class="form-control payment_method_id" required>
                                                <option value="">@lang('Select Method')</option>
                                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                                    <option data-accounts="{{ $method->accounts }}"
                                                        @selected(old('payment_method_id' == @$method->id)) value="{{ $method->id }}">
                                                        {{ $method->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label class="form-label text-start">@lang('Account')</label>
                                            <select name="account_id" id="account_id" class="form-control account_id"
                                                required>
                                                <option value="">@lang('Select Account')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Total Amount')</label>
                                            <input type="text" value="{{ en2bn($festivalbonusdetail->amount) }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Payment Amount') <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" value="" name="amount" class="form-control"  required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Date')</label>
                                            <input type="date" value="{{ Date('Y-m-d') }}" name="date" class="form-control">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('Close')</button>
                                <input type="submit" class="btn btn-primary float-end"
                                    onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                    value="@lang('Submit')">
                            </div>
                        </div>
                    </form>
                    @push('script')
                        <script>
                            $('[name=payment_method_id]').on('change', function() {

                                var accounts = $(this).find('option:selected').data('accounts');
                                var option = '<option value="">Select Account</option>';
                                $.each(accounts, function(index, value) {
                                    var name = value.title;
                                    option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                                        name + "</option>";
                                });

                                $('select[name=account_id]').html(option);

                            }).change();
                        </script>
                    @endpush
                </div>
            </div>



        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hovered">
                    <tbody>
                        <tr>
                            <th>Employee</th>
                            <th>:</th>
                            <td>{{ optional($festivalbonusdetail->employee)->name }}</td>
                            <th>Amount</th>
                            <th>:</th>
                            <td>{{ en2bn(number_format($festivalbonusdetail->amount)) }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <th>:</th>
                            <td> 
                                <button class="btn btn-success btn-sm">{{ $festivalbonusdetail->status }}</button>
                            </td>
                            <th>Festival Bonus </th>
                            <th>:</th>
                            <td>{{ optional($festivalbonusdetail->festivalbonus)->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
     @if ($payments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Payment</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>@lang('Employee')</th>
                                <th>@lang('Payment Method')</th>
                                <th>@lang('Account')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Entry By')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $item)
                                <tr>
                                    <td> {{ optional($item->employee)->name }} </td>
                                    <td> {{ optional($item->paymentmethod)->name }}</td>
                                    <td> {{ optional($item->account)->title }}</td>
                                    <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }} </td>
                                    <td> {{ en2bn(Date('d-m-Y', strtotime($item->date))) }}</td>
                                    <td> {{ optional($item->entryuser)->name }}</td>
                                    <td> 
                                        <button class="btn btn-success btn-sm">{{ $item->status }}</button>
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
                                <th colspan="2"></th>
                                <th>@lang('Total')</th>
                                <th>{{ en2bn(number_format($payments->sum('amount'), 2, '.', ',')) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table><!-- table end -->
                </div>
            </div>
        </div>
    @endif
@endsection

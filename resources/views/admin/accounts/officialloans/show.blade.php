@extends('admin.layouts.app', ['title' => __('Official Loan Show')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">@lang('Official Loan Show')
                <a href="{{ route('admin.officialloan.create') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-plus"></i> @lang('Add New Official Loan')</a>

                <a href="{{ route('admin.officialloan.index') }}" class="btn btn-outline-primary btn-sm float-end me-2"> <i
                        class="bi bi-list"></i> @lang('Official Loan List')</a>

                <a href="#" class="btn btn-sm btn-outline-success float-end me-2" data-bs-toggle="modal"
                    data-bs-target="#LoanPayment">
                    <i class="fa fa-money-bill"></i> @lang('Make Payment')
                </a>
            </h6>
        </div>

        <div class="modal fade" id="LoanPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.officialloanpayment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="official_loan_id" value="{{ $officialloan->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Official Loan Payment')</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Payment Method')</label>
                                        <select name="payment_method_id" id="payment_method_id"
                                            class="form-control payment_method_id" required>
                                            <option value="">@lang('Select Method')</option>
                                            @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                                <option data-accounts="{{ $method->accounts }}" @selected(old('payment_method_id' == @$method->id))
                                                    value="{{ $method->id }}">
                                                    {{ $method->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label class="form-label text-start">@lang('Account')</label>
                                        <select name="account_id" id="account_id" class="form-control account_id" required>
                                            <option value="">@lang('Select Account')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Total Due')</label>
                                        <input type="text" value="" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Amount') <span
                                                class="text-danger">*</span> </label>
                                        <input type="text" value="" name="amount" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Date')</label>
                                        <input type="date" value="{{ Date('Y-m-d') }}" name="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="py-2 form-group">
                                        <label for="" class="form-label">@lang('Note')</label>
                                        <textarea name="note" id="note" class="form-control"></textarea>
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


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('Title')</th>
                            <td>{{ $officialloan->title }}</td>
                            <th>@lang('Date')</th>
                            <td>{{ en2bn(Date('d-m-Y', strtotime($officialloan->date))) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Month')</th>
                            <td> {{ optional($officialloan->month)->name }} - {{ optional($officialloan->year)->name }}
                            </td>
                            <th>@lang('Account')</th>
                            <td>{{ optional($officialloan->account)->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Amount')</th>
                            <td> {{ en2bn(number_format($officialloan->amount)) }}</td>
                            <th>@lang('Interest')</th>
                            <td> {{ en2bn(number_format($officialloan->interest)) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Total Amount')</th>
                            <td> {{ en2bn(number_format($officialloan->total_amount)) }}</td>
                            <th>@lang('Monthly Installment')</th>
                            <td> {{ en2bn(number_format($officialloan->monthly_settlement)) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Note')</th>
                            <td colspan="3"> {{ $officialloan->note }}</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">
                Official Loan Payment History
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Payment Method')</th>
                            <th>@lang('Account')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>
                            <th>@lang('Entry User')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($officialloanpayments as $item)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.officialloanpayment.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->date }}</td>
                                <td class="text-start"> {{ optional($item->officialloan)->title }} </td>
                                <td> {{ optional($item->paymentmethod)->name }} </td>
                                <td> {{ optional($item->account)->title }} </td>
                                <td> {{ en2bn(number_format($item->amount, 2, '.', ',')) }}</td>
                                <td> {{ $item->note }}</td>
                                <td> 
                                    {!! entry_info($item) !!}
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
                            <th colspan="6">@lang('Total')</th>
                            <th>{{ en2bn(number_format($officialloanpayments->sum('amount'), 2, '.', ',')) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection

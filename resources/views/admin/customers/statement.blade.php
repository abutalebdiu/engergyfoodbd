@extends('admin.layouts.app', ['title' => 'Customer Account Statement'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                data-bs-target="#customer_payment">
                <i class="fa fa-money-bill"></i> @lang('Receive Payment')
            </a>
 
            <!-- Modal -->
            <div class="modal fade" id="customer_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.customerduepayment.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Customer Due Receive') </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">@lang('Customer')
                                                {{ $customer->name }}</label>
                                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
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
                                            <label for="" class="form-label">@lang('Pay Amount')</label>
                                            <input type="text" value="" name="amount"
                                                class="form-control pay_amount">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">@lang('Date')</label>
                                            <input type="date" value="{{ Date('Y-m-d') }}" name="date"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"> @lang('Submit')</button>
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
            <div class="row">
                <div class="col-12 col-md-6">
                    <h4 class="pb-2 mb-3 border-bottom">@lang('Customer Information')</h4>
                    <p>
                        @lang('ID'): {{ en2bn($customer->uid) }} <br>
                        @lang('Date'): {{ $customer->name }} <br>
                        @lang('Mobile'): {{ $customer->mobile }} <br>
                        @if ($customer->email)
                            @lang('Email'): {{ $customer->email }} <br>
                        @endif
                        @lang('Address'): {{ $customer->address }} <br>
                        @lang('Commission Type')</b> : {{ __($customer->commission_type) }}
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h4 class="pb-2 mb-3 border-bottom">@lang('Customer Financial Information')</h4>
                    <p>
                        Total Order : {{ en2bn(number_format($totalorders, 2, '.', ',')) }}<br>
                        Total Amount : {{ en2bn(number_format($totalamount, 2, '.', ',')) }} <br>
                        Opening Due : {{ en2bn(number_format($opening_due, 2, '.', ',')) }} <br>
                        Total Paid : {{ en2bn(number_format($total_paid, 2, '.', ',')) }} <br>
                        Total Commission Paid : {{ en2bn(number_format($total_commission_paid, 2, '.', ',')) }} <br>
                        Total Return : {{ en2bn(number_format($totalreturns, 2, '.', ',')) }}<br>
                        Total Advance: {{ en2bn(number_format($total_advance, 2, '.', ',')) }}<br>
                        Total Due Payment: {{ en2bn(number_format($total_due_payment, 2, '.', ',')) }}<br>
                        Total Due : {{ en2bn(number_format($total_due, 2, '.', ',')) }}<br>
                    </p>
                </div>
            </div>


        </div>
    </div>


    @include('admin.customers.history_tab')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.pay_amount').on('keyup', function() {
                // var amount = $(this).val();
                // var due = $('.total_due').val();
                // var balance = due - amount;
                // $('.less_amount').val(balance);

            });

            var due = $('.total_due').val();

            if (due == 0) {
                $('.less_amount').attr('disabled', true);
            }
        });
    </script>
@endpush

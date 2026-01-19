@extends('admin.layouts.app', ['title' => 'Supplier Account Statement'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <a href="#" class="btn btn-sm btn-outline-success float-end" data-bs-toggle="modal"
                data-bs-target="#customer_payment">
                <i class="fa fa-money-bill"></i> Pay Payment
            </a>

            <!-- Modal -->
            <div class="modal fade" id="customer_payment" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('admin.supplierduepayment.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Supplier Due Payment </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="form-label">Supplier: {{ $supplier->name }}</label>
                                            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Payment
                                                Method</label>
                                            <select name="payment_method_id" id="payment_method_id"
                                                class="form-control payment_method_id" required>
                                                <option value="">Select Method</option>
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
                                            <label for="" class="form-label">Pay Amount</label>
                                            <input type="text" value="" name="amount"
                                                class="form-control pay_amount">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="py-2 form-group">
                                            <label for="" class="form-label">Date</label>
                                            <input type="date" value="{{ Date('Y-m-d') }}" name="date"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
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
                    <h4 class="pb-2 mb-3 border-bottom">Supplier Information</h4>
                    <p>
                        Name: {{ @$supplier->name }} <br>
                        Mobile: {{ @$supplier->mobile }} <br>
                        @if (@$supplier->email)
                            Email : {{ @$supplier->email }} <br>
                        @endif

                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h4 class="pb-2 mb-3 border-bottom">Supplier Financial Information</h4>
                    <p>
                        Total Opending Due : {{ en2bn(number_format($supplier->opening, 0, '.', ',')) }}<br>
                        Total Order : {{ en2bn(number_format($totalorders, 0, '.', ',')) }}<br>
                        Total Amount : {{ en2bn(number_format($totalamount, 2, '.', ',')) }} <br>
                        Total Item Payment: {{ en2bn(number_format($totalitempayment, 2, '.', ',')) }} <br>
                        Total Item Due Payment: {{ en2bn(number_format($totalsupplierduepayment, 2, '.', ',')) }} <br>
                        Total Payable : {{ en2bn(number_format($supplier->payable($supplier->id), 2, '.', ',')) }} <br>
                    </p>
                </div>
            </div>


        </div>
    </div>


    @include('admin.users.history_tab')
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

@extends('admin.layouts.app', ['title' => 'Edit Order Payment'])
@section('panel')
    <form action="{{ route('admin.orderpayment.update', $orderpayment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Order Payment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Buyer')</label>
                            <select class="form-select buyer_id" name="buyer_id">
                                <option value="">Select Buyer</option>
                                @foreach ($buyers as $buyer)
                                    <option data-unpaidorders="{{ $buyer->unpaidorders }}" @selected(old('buyer_id', @$orderpayment->buyer_id == @$buyer->id))
                                        value="{{ $buyer->id }}">
                                        {{ $buyer->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Payment Date')</label>
                            <input type="date" class="form-control" name="date" value="{{ $orderpayment->date }}"
                                required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Orders</label>
                        <select class="form-select order_id" name="order_id" required>
                            <option value="">Select Order</option>

                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="">Amount</label>
                            <input type="text" name="amount" value="{{ $orderpayment->amount }}" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control">{{ $orderpayment->note }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control payment_method_id"
                                required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option value="{{ $method->id }}" data-accounts="{{ $method->accounts }}"
                                        @selected(old('payment_method_id', @$orderpayment->payment_method_id == @$method->id))>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Mother Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Buyer Account')</label>
                            <select name="buyer_account_id" id="buyer_account_id" class="form-control buyer_account_id"
                                required>
                                <option value="">@lang('Select Buyer Account')</option>
                                @foreach ($buyeraccounts as $buyeraccount)
                                    <option {{ $orderpayment->buyer_account_id == $buyeraccount->id ? 'selected' : '' }}
                                        value="{{ $buyeraccount->id }}">{{ $buyeraccount->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group py-2">
                            <a href="{{ route('admin.orderpayment.index') }}"
                                class="btn btn-outline-info float-start">Back</a>
                            <button type="submit" class="btn btn-primary float-end"
                                onclick="return confirm('Are you sure! Submit This Amount')">@lang('Submit')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('script')
    <script>
        $('[name=payment_method_id]').on('change', function() {
            var accounts = $(this).find('option:selected').data('accounts');
            var option = '<option value="">Select Account</option>';
            $.each(accounts, function(index, value) {
                var name = value.title;
                option += "<option value='" + value.id + "' " + (value.id ==
                        "{{ $orderpayment->account_id }}" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();


        $('[name=buyer_id]').on('change', function() {
            var unpaidorders = $(this).find('option:selected').data('unpaidorders');
            var option = '<option value="">Select Order</option>';
            $.each(unpaidorders, function(index, value) {
                var name = value.oid;
                var tt_lc_no = value.tt_lc;

                option += "<option value='" + value.id + "' " + (value.id ==
                        "{{ $orderpayment->order_id }}" ? "selected" : "") + ">" +
                    name + " ( " + tt_lc_no + " )</option>";
            });
            $('select[name=order_id]').html(option);
        }).change();

        $('.payment_method_id').on('change', function() {
            var payment_method_id = $('.payment_method_id').val();
            var buyer_id = $('.buyer_id').val();

            if (payment_method_id && buyer_id) {
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.get.buyer.account.bybuyer') }}",
                    data: {
                        payment_method_id: payment_method_id,
                        buyer_id: buyer_id
                    },
                    success: function(data) {
                        $(".buyer_account_id").html(data);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    </script>
@endpush

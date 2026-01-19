@extends('admin.layouts.app', ['title' => 'Add New Supplier Payment'])
@section('panel')
    <form action="{{ route('admin.ordersupplierpayment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Supplier Payment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Supplier')</label>
                            <select class="form-select" name="supplier_id">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option data-supplierunpaidorders="{{ $supplier->supplierunpaidorders }}"
                                        @selected(old('supplier_id' == @$supplier->id)) value="{{ $supplier->id }}">
                                        {{ $supplier->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Payment Date')</label>
                            <input type="date" class="form-control" name="date"
                                value="{{ old('date') ? old('date') : date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Orders</label>
                        <select class="form-select order_detail_id" name="order_detail_id" required>
                            <option value="">Select Order</option>

                        </select>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Amount</label>
                            <input type="text" name="amount" value="{{ old('amount') }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control payment_method_id"
                                required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option data-accounts="{{ $method->accounts }}" @selected(old('payment_method_id' == @$method->id))
                                        value="{{ $method->id }}">
                                        {{ $method->name }} </option>
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
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group py-2">
                            <a href="{{ route('admin.ordersupplierpayment.index') }}"
                                class="btn btn-outline-info float-start">Back</a>
                            <button type="submit" value="Submit" class="btn btn-primary float-end" onclick="this.form.submit(); this.disabled=true; this.value='Submittong…';">@lang('Submit')
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
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();

        $('[name=supplier_id]').on('change', function() {
            var supplierunpaidorders = $(this).find('option:selected').data('supplierunpaidorders');
            var option = '<option value="">Select Order</option>';
            $.each(supplierunpaidorders, function(index, value) {
                var name = value.order.oid;
                var styleno = value.name;
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + " ( " + styleno + " ) - ("+ value.amount +" tk) </option>";
            });

            $('select[name=order_detail_id]').html(option);
        }).change();


        $('.account_id').on('change', function() {
            var payment_method_id = $('.payment_method_id').val();
            var order_detail_id = $('.order_detail_id').val();

            if (payment_method_id && order_detail_id) {
                $.ajax({
                    type: "get",
                    url: "{{ route('admin.get.buyer.account') }}",
                    data: {
                        payment_method_id: payment_method_id,
                        order_detail_id: order_detail_id
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

@include('components.select2')

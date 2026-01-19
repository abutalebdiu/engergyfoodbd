@extends('admin.layouts.app', ['title' => 'Add New Order Payment'])
@section('panel')
    <form action="{{ route('admin.orderpayment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Order Payment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Customer') <span
                                    class="text-danger">*</span></label>
                            <select class="form-select customer_id" name="customer_id">
                                <option value="">Select Customer</option>
                                @foreach ($buyers as $buyer)
                                    <option data-unpaidorders="{{ $buyer->unpaidorders }}" @selected(old('customer_id' == @$buyer->id))
                                        value="{{ $buyer->id }}">
                                        {{ $buyer->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Payment Date') <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date"
                                value="{{ old('date') ? old('date') : date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Orders <span class="text-danger">*</span></label>
                        <select class="form-select order_id" name="order_id" required>
                            <option value="">Select Order</option>

                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="">Amount (BDT) <span class="text-danger">*</span></label>
                            <input type="text" name="amount" value="{{ old('amount') }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label for="">Note</label>
                            <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method <span
                                    class="text-danger">*</span></label>
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
                            <label class="form-label">@lang('Mother Account') <span class="text-danger">*</span></label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
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
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();

        $('[name=customer_id]').on('change', function() {
            var unpaidorders = $(this).find('option:selected').data('unpaidorders');
            var option = '<option value="">Select Order</option>';
            $.each(unpaidorders, function(index, value) {
                var name = value.oid;
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + " ( " + "tk  " + value.totalamount + ")  </option>";
            });

            $('select[name=order_id]').html(option);
        }).change();



    </script>
@endpush

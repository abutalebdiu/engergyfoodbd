@extends('admin.layouts.app',['title'=>'Add New Services Payment'])
@section('panel')
    <form action="{{ route('admin.serviceinvoicepayment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Services Payment <a href="{{ route('admin.serviceinvoicepayment.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Services Payment History List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-select" required>
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
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-select account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Customer') <span
                                    class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($customers as $item)
                                    <option data-unpaidinvoice="{{ $item->unpaidinvoice }}" value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="service_invoice_id" id="service_invoice_id"
                                class="form-select service_invoice_id">
                                <option value=""> -- Select -- </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" required>
                        </div>
                    </div>  
                </div>

                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100 mt-4">@lang('Submit')
                    </button>
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
    </script>
@endpush

@push('script')
    <script>
        $('[name=customer_id]').on('change', function() {
            var unpaidinvoice = $(this).find('option:selected').data('unpaidinvoice');
            var option = '<option value="">-- Select --</option>';
            $.each(unpaidinvoice, function(index, value) {
                var month = value.month.name;
                var year = value.year.name;
                var amount = value.amount;
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    month + "-" + year  +  " (" + amount +")" +"</option>";
            });

            $('select[name=service_invoice_id]').html(option);
        }).change();
    </script>
@endpush

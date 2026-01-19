@extends('admin.layouts.app', ['title' => 'Add New Asset Expenses Payment'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.assetexpensepayment.index') }}" class="btn btn-sm btn-outline-primary">@lang('Asset Expense Payments')</a>
        </div>
    @endpush

    <form action="{{ route('admin.assetexpensepayment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Add New Asset Expense Payment') </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Asset Expense')</label>
                            <select class="form-select" name="asset_expense_id" required>
                                <option value="">@lang('Select One')</option>
                                @foreach ($assetexpenses as $item)
                                    <option {{ old('payment_id') == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="py-2 form-group">
                            <label for="" class="form-label">Payment
                                Method</label>
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
                    <div class="col-12 col-md-6 pb-3">
                        <div class="form-group">
                            <label class="form-label text-start">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pb-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ old('date') ? old('date') : Date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pb-3 ">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Amount')</label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Note')</label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-12 mt-md-4">
                        <a href="{{ route('admin.assetexpensepayment.index') }}"
                            class="btn btn-outline-info float-start">Back</a>
                        <input type="submit" class="btn btn-primary float-end"
                            onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                            value="@lang('Submit')">
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
    </script>
@endpush

@extends('admin.layouts.app', ['title' => 'Edit Expense Payment History'])
@section('panel')
    <form action="{{ route('admin.expensepaymenthistory.update', $expensepaymenthistory->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Expense Payment History
                    <a href="{{ route('admin.expensepaymenthistory.index') }}"
                        class="btn btn-outline-primary btn-sm float-end">
                        <i class="bi bi-list"></i>
                        Expense Payment History List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">@lang('Payment Method')</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option value="{{ $method->id }}" data-accounts="{{ $method->accounts }}"
                                        @selected(old('payment_method_id', @$expensepaymenthistory->payment_method_id == @$method->id))>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount"
                                value="{{ $expensepaymenthistory->amount }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Date') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date"
                                value="{{ $expensepaymenthistory->date }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <input type="submit" class="btn btn-primary float-end"
                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                        value="@lang('Submit')">
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
                        "{{ $expensepaymenthistory->account_id }}" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();
    </script>
@endpush

@extends('admin.layouts.app', ['title' => 'Edit Monthly Expenses Payment'])
@section('panel')
    @push('breadcrumb-plugins')
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.monthlyexpensepayment.index') }}"
                class="btn btn-sm btn-outline-primary">@lang('Monthly Expense Payments')</a>
        </div>
    @endpush

    <form action="{{ route('admin.monthlyexpensepayment.update',$monthlyexpensepayment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Edit Monthly Expense Payment') </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Monthly Expense')</label>
                            <select class="form-select" name="monthly_expense_id" required>
                                <option value="">@lang('Select One')</option>
                                @foreach ($monthlyexpenses as $item)
                                    <option {{ $monthlyexpensepayment->monthly_expense_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 pb-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Payment Method')</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option value="{{ $method->id }}" data-accounts="{{ $method->accounts }}"
                                        @selected(old('payment_method_id', @$monthlyexpensepayment->payment_method_id == @$method->id))>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pb-3">
                        <div class="form-group">
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 pb-3">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Date')</label>
                            <input class="form-control" type="date" name="date"
                                value="{{ $monthlyexpensepayment->date }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pb-3 ">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Amount')</label>
                            <input class="form-control" type="text" name="amount" value="{{ $monthlyexpensepayment->amount }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pb-3 ">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Month') <span
                                    class="text-danger">*</span></label>
                            <select name="month_id" id="month_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $month)
                                    <option {{ $monthlyexpensepayment->month_id == $month->id ? "selected" : "" }} value="{{ $month->id }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Note')</label>
                            <textarea name="note" id="note" class="form-control">{{ $monthlyexpensepayment->note }}</textarea>
                        </div>
                    </div>
                    <div class="col-12 mt-md-4">
                        <a href="{{ route('admin.monthlyexpensepayment.index') }}"
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
                option += "<option value='" + value.id + "' " + (value.id ==
                        "{{ $monthlyexpensepayment->account_id }}" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();
    </script>
@endpush

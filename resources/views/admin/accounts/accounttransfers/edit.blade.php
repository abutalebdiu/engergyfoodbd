@extends('admin.layouts.app', ['title' => 'New Account Balance Transfer'])
@section('panel')
    <form action="{{ route('admin.accounttransfer.update',$accounttransfer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Balance Transfer <a href="{{ route('admin.accounttransfer.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Account Transfer List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">From Payment Method</label>
                            <select name="from_payment_method_id" id="from_payment_method_id" class="form-control" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option value="{{ $method->id }}" data-fromaccounts="{{ $method->accounts }}"
                                        @selected(old('from_payment_method_id', @$accounttransfer->from_payment_method_id == @$method->id))>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('From Account')</label>
                            <select name="from_account_id" id="from_account_id" class="form-control from_account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>                  
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">To Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option value="{{ $method->id }}" data-accounts="{{ $method->accounts }}"
                                        @selected(old('payment_method_id', @$accounttransfer->payment_method_id == @$method->id))>
                                        {{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('To Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ $accounttransfer->amount }}" required>
                        </div>
                    </div>    
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Note')</label>
                            <textarea name="note" id="note" class="form-control">{{ $accounttransfer->note }}</textarea>
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
          $('[name=from_payment_method_id]').on('change', function() {
            var fromaccounts = $(this).find('option:selected').data('fromaccounts');
            var option = '<option value="">Select Account</option>';
            $.each(fromaccounts, function(index, value) {
                var name = value.title;
                option += "<option value='" + value.id + "' " + (value.id ==
                        "{{ $accounttransfer->from_account_id }}" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=from_account_id]').html(option);
        }).change(); 
    </script>
    <script>
        $('[name=payment_method_id]').on('change', function() {
            var accounts = $(this).find('option:selected').data('accounts');
            var option = '<option value="">Select Account</option>';
            $.each(accounts, function(index, value) {
                var name = value.title;
                option += "<option value='" + value.id + "' " + (value.id ==
                        "{{ $accounttransfer->account_id }}" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();
  </script>
@endpush
@include('components.select2')
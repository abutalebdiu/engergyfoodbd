@extends('admin.layouts.app', ['title' => 'Edit Customer Settlement'])
@section('panel')
    <form action="{{ route('admin.customersettlement.update', $customer_settlement->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Edit Customer Settlement <a href="{{ route('admin.customersettlement.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> Customer Settlement List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Date') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date" value="{{ $customer_settlement->date }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach (App\Models\User::where('type', 'customer')->where('status', 1)->get() as $customer)
                                    <option  @selected($customer_settlement->customer_id == @$customer->id)
                                        value="{{ $customer->id }}">{{ $customer->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label for="" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">Select Method</option>
                                @foreach (App\Models\Account\PaymentMethod::with('accounts')->get() as $method)
                                    <option data-accounts="{{ $method->accounts }}" @selected($customer_settlement->payment_method_id == @$method->id)
                                        value="{{ $method->id }}">
                                        {{ $method->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-control account_id" required>
                                @foreach (\App\Models\Account\Account::get() as $account)
                                    <option value="{{ $account->id }}" @selected($customer_settlement->account_id == $account->id)>{{ $account->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Type')</label>
                            <select name="type" id="type" class="form-control type" required>
                                <option value="">@lang('Select Type')</option>
                                <option value="Plus" {{ $customer_settlement->type == 'Plus' ? 'selected' : '' }}>Plus</option>
                                <option value="Minus" {{ $customer_settlement->type == 'Minus' ? 'selected' : '' }}>Minus</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ $customer_settlement->amount }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Note') <span
                                    class="text-danger">*</span></label>
                            <textarea name="note" id="note" class="form-control">
                                {{ $customer_settlement->note }}
                            </textarea>
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
@include('components.select2')

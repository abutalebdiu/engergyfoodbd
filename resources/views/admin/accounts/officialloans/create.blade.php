@extends('admin.layouts.app', ['title' => 'Add New Official Loan'])
@section('panel')
    <form action="{{ route('admin.officialloan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Add New Official Loan <a href="{{ route('admin.officialloan.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="bi bi-list"></i> Official Loan List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Title') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
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
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label">@lang('Account')</label>
                            <select name="account_id" id="account_id" class="form-select account_id" required>
                                <option value="">@lang('Select Account')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Date') <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="date" value="{{ old('date')?old('date'):Date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Note') </label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Installment Start Month') <span
                                    class="text-danger">*</span></label>
                            <select name="start_month_id" id="start_month_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($months as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Year') <span
                                    class="text-danger">*</span></label>
                            <select name="year_id" id="year_id" class="form-select">
                                <option value=""> -- Select -- </option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="amount" value="{{ old('amount') }}" required>
                        </div>
                    </div>



                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('interest') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="interest" value="{{ old('interest') }}"
                                required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('total Amount') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="total_amount"
                                value="{{ old('total_amount') }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Monthly Installment') <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="monthly_settlement"
                                value="{{ old('monthly_settlement') }}" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group py-2">
                            <label class="form-label text-capitalize">@lang('Attachment') </label>
                            <input class="form-control" type="file" name="attachment"
                                value="{{ old('attachment') }}">
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
                option += "<option value='" + value.id + "' " + (value.id == "" ? "selected" : "") + ">" +
                    name + "</option>";
            });

            $('select[name=account_id]').html(option);
        }).change();
    </script>
@endpush

@extends('admin.layouts.app', ['title' => 'Add New Account'])
@section('panel')
    <form action="{{ route('admin.account.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Add New Account') }} <a href="{{ route('admin.account.index') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i> @lang('Account List')</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Payment Method') <span class="text-danger">*</span></label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">@lang('Select Payment Method')</option>
                                @foreach ($paymentmethods as $item)
                                    <option {{ old('payment_method_id') == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Title') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Account Name') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="account_name"
                                value="{{ old('account_name') }}" required>
                        </div>
                    </div>

                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Account Number') <span class="text-danger">*</span></label>
                            <input type="number" name="account_number" value="{{ old('account_number') }}"
                                id="account_number" class="form-control" required>
                        </div>
                    </div>

                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('branch')</label>
                            <input class="form-control" type="text" name="branch" value="{{ old('branch') }}">
                        </div>
                    </div>

                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('routing')</label>
                            <input class="form-control" type="text" name="routing" value="{{ old('routing') }}">
                        </div>
                    </div>

                    <div class="pb-3 col-12 col-md-6">
                        <div class="form-group ">
                            <label class="form-label text-capitalize">@lang('opening balance') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="opening_balance"
                                value="{{ old('opening_balance') }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Status') <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="">@lang('Select Status')</option>
                                <option {{ old('status') == 'Active' ? 'selected' : '' }} value="Active">@lang('Active')</option>
                                <option {{ old('status') == 'Pending' ? 'selected' : '' }} value="Pending">@lang('Pending')</option>
                                <option {{ old('status') == 'Inactive' ? 'selected' : '' }} value="Inactive">@lang('Pending')</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <button type="submit" class="mt-4 btn btn-primary w-100">@lang('Submit')
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

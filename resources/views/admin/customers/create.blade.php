@extends('admin.layouts.app', ['title' => 'Add New Customer'])
@section('panel')
    <form id="customerForm" action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"> @lang('Add New Customer') <a href="{{ route('admin.customers.all') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i>@lang('All Customers')</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Name') <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Company Name')</label>
                            <input class="form-control" type="text" name="company_name"
                                value="{{ old('company_name') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Email') </label>
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Mobile') </label>
                            <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 pb-3">
                        <div class="form-group">
                            <label for="">Own Whatsapp Number</label>
                            <input type="text" name="own_whatsapp" class="form-control"
                                value="{{ old('own_whatsapp') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 pb-3">
                        <div class="form-group">
                            <label for="">Opening Due Amount</label>
                            <input type="text" name="opening" class="form-control"
                                value="{{ old('opening') ? old('opening') : '0' }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-12">
                        <div class="form-group ">
                            <label class="form-label">@lang('Address')</label>
                            <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group ">
                            <label class="form-label">@lang('Commission Type')</label>
                            <select name="commission_type" id="commission_type" class="form-select">
                                <option value="Monthly">Monthly</option>
                                <option value="Daily">Daily</option>
                            </select>
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group ">
                            <label class="form-label">@lang('Commission')</label>
                            <input type="number" name="commission" value="{{ old('commission') ? old('commission') : 6 }}" step="0.01" class="form-control" placeholder="Enter Customer Commission">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group ">
                            <label class="form-label">@lang('Marketer Name')</label>
                            <select name="reference_id" id="reference_id" class="form-select select2 reference_id">
                                <option value="">Select Marketer</option>
                                @foreach ($marketers as $marketer)
                                    <option {{ old('reference_id') == $marketer->id ? 'selected' : '' }}
                                        value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="pb-3 col-md-4">
                        <div class="form-group ">
                            <label class="form-label">@lang('Distributor Name')</label>
                            <select name="distribution_id" id="distribution_id" class="form-select select2 distribution_id">
                                <option value="">Select Distributor</option>
                                @foreach ($distributors as $distributor)
                                    <option {{ old('distribution_id') == $distributor->id ? 'selected' : '' }}
                                        value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="col-12">
                        <button type="submit" id="submitBTN" class="btn btn-primary mt-4"> <i class="fa fa-submit"></i> @lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@include('components.select2')


@push('script')
<script>
    $(document).on('submit', '#customerForm', function(e) {
        e.preventDefault();

        let formId = $(this);

        storeData(formId, "submitBTN");

    });
</script>
@endpush

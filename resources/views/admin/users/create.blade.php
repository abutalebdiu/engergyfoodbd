@extends('admin.layouts.app', ['title' => 'Add New Supplier'])
@section('panel')
    <form id="supplierForm" action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Supplier</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Company Name')</label>
                            <input class="form-control" type="text" name="company_name" required
                                value="{{ old('company_name') }}">
                        </div>
                    </div>


                    <div class="pb-3 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Mobile Number') </label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                class="form-control">

                        </div>
                    </div>

                    <div class="pb-3 col-md-4">
                        <div class="form-group ">
                            <label class="form-label">@lang('Address')</label>
                            <input class="form-control" type="text" name="address" value="{{ old('address') }}">
                        </div>
                    </div>


                    <div class="col-12 col-md-4 pb-3">
                        <div class="form-group">
                            <label for="" class="form-label">Payable Amount</label>
                            <input type="text" name="opening" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('admin.suppliers.all') }}" class="btn btn-outline-info mt-4"> <i
                                class="fa fa-arrow-alt-circle-left"></i> Back</a>
                        <button type="submit" id="submitBTN" class="btn btn-primary mt-4"><i class="fa fa-check"></i> @lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict"
            $('.bal-btn').click(function() {
                var act = $(this).data('act');
                $('#addSubModal').find('input[name=act]').val(act);
                if (act == 'add') {
                    $('.type').text('Add');
                } else {
                    $('.type').text('Subtract');
                }
            });
            let mobileElement = $('.mobile-code');
            $('select[name=country]').change(function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            });

            $('select[name=country]').val('');
            let dialCode = $('select[name=country] :selected').data('mobile_code');
            mobileNumber = mobileNumber.replace(dialCode, '');
            $('input[name=mobile]').val(mobileNumber);
            mobileElement.text(`+${dialCode}`);

        })(jQuery);
    </script>
@endpush


@push('script')
<script>
    $(document).on('submit', '#supplierForm', function(e) {
        e.preventDefault();

        let formId = $(this);

        storeData(formId, "submitBTN");

    });
</script>
@endpush


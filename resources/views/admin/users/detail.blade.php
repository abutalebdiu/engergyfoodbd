@extends('admin.layouts.app', ['title' => 'Supplier Detail - ' . $user->name])

@section('panel')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-4">
        <div class="col">
            <div class="card radius-10 border-0 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Orders</p>
                            <h4 class="mb-0 text-primary">{{ $user->supplierorders->count() }}</h4>
                        </div>
                        <a href="#" class="ms-auto widget-icon bg-primary text-white">
                            <i class="bi bi-bag-check-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-0 border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Amount</p>
                            <h4 class="mb-0 text-success">{{ number_format($user->supplierorders->sum('amount'),2) }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-success text-white">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-0 border-start border-secondary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Paid Amount</p>
                            <h4 class="mb-0 text-secondary">{{ number_format($user->supplierspayment->sum('amount'),2) }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-secondary text-white">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-0 border-start border-danger border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Unpaid Amount</p>
                            <h4 class="mb-0 text-danger">{{ number_format($user->supplierorders->sum('amount') - $user->supplierspayment->sum('amount'),2) }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-danger text-white">
                            <i class="bi bi-bag-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="profile-img d-flex gap-4 flex-wrap">
                        <div>
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image) }}" alt="">
                        </div>
                        <div>
                            <h5>{{ $user->name }}</h5>
                            <h6>{{ $user->company_name }}</h6>
                            <h6>{{ $user->email }}</h6>
                            <h6>{{ $user->mobile }}</h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-12 col-lg-7">
            <form action="{{ route('admin.suppliers.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" required
                                        value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Company Name')</label>
                                    <input class="form-control" type="text" name="company_name" required
                                        value="{{ $user->company_name }}">
                                </div>
                            </div>
                            

                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Mobile Number') </label>
                                    <div class="input-group">
                                        <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                            class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>

                            <div class="pb-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Address')</label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ @$user->address->address }}">
                                </div>
                            </div> 
 
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Payable Amount</label>
                                    <input type="text" name="opening" class="form-control" value="{{ $user->opening }}">
                                </div>
                            </div>
                        </div>
                        <div class="div">
                            <button type="submit" class="btn btn-primary w-100 mt-4">@lang('Submit')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>





    <x-confirmation-modal />
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

            $('select[name=country]').val('{{ @$user->country_code }}');
            let dialCode = $('select[name=country] :selected').data('mobile_code');
            let mobileNumber = `{{ $user->mobile }}`;
            mobileNumber = mobileNumber.replace(dialCode, '');
            $('input[name=mobile]').val(mobileNumber);
            mobileElement.text(`+${dialCode}`);

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .profile-img img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }
    </style>
@endpush

@extends('admin.layouts.app', ['title' => 'Customer Detail - ' . $user->username])

@section('panel')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-4">
        <div class="col">
            <div class="card radius-10 border-0 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Orders</p>
                            <h4 class="mb-0 text-primary">{{ $totalorders }}</h4>
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
                            <p class="mb-1">Delivered Order</p>
                            <h4 class="mb-0 text-success">{{ $deliveredorder }}</h4>
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
                            <p class="mb-1">Pending Order</p>
                            <h4 class="mb-0 text-secondary">{{ $pendingorder }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-secondary text-white">
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
                            <p class="mb-1">Total Due Amount</p>
                            <h4 class="mb-0 text-secondary">{{ $pendingorder }}</h4>
                        </div>
                        <div class="ms-auto widget-icon bg-secondary text-white">
                            <i class="bi bi-currency-dollar"></i>
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
                            <h5>@lang('ID'): {{ en2bn($user->uid) }}</h5>
                            <h5>@lang('Name'): {{ $user->name }}</h5>
                            <h6>@lang('Company Name'): {{ $user->company_name }}</h6>
                            <h6>@lang('Email'): {{ $user->email }}</h6>
                            <h6>@lang('Mobile'): {{ $user->mobile }}</h6>
                            <h6>@lang('Address'): {{ $user->address }}</h6>
                            <h6>@lang('Commission Type'): {{ __($user->commission_type) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <form action="{{ route('admin.customers.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            @lang('Edit Customer Detail')
                            <a href="{{ route('admin.customers.all') }}" class="btn btn-primary btn-sm float-end"> <i
                                    class="fa fa-list"></i> @lang('Customer List')</a>
                        </h5>
                    </div>
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
                                    <label class="form-label">@lang('ID')</label>
                                    <input class="form-control" type="text" name="uid" required
                                        value="{{ $user->uid }}">
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
                                    <label class="form-label">@lang('Email') </label>
                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="pb-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('Mobile') </label>
                                    <input type="text" name="mobile" value="{{ en2bn($user->mobile) }}" id="mobile"
                                        class="form-control checkUser" required>
                                </div>
                            </div>

                            <div class="pb-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" value="{{ $user->address }}">
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Commission Type')</label>
                                    <select name="commission_type" id="commission_type" class="form-select">
                                        <option {{ $user->commission_type == 'Monthly' ? 'selected' : '' }}
                                            value="Monthly">Monthly</option>
                                        <option {{ $user->commission_type == 'Daily' ? 'selected' : '' }} value="Daily">
                                            Daily</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-12 col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Commission')</label>
                                    <input type="number" name="commission" value="{{ $user->commission }}"
                                        step="0.01" class="form-control" placeholder="Enter Customer Commission">
                                </div>
                            </div>
                            <div class="pb-3 col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Opening Due Amount</label>
                                    <input type="text" name="opening" class="form-control"
                                        value="{{ en2bn($user->opening_due) }}">
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group ">
                                    <label class="">@lang('Marketer Name')</label>
                                    <select name="reference_id" id="reference_id"
                                        class="form-select select2 reference_id">
                                        <option value="">Select Marketer</option>
                                        @foreach ($marketers as $marketer)
                                            <option {{ $user->reference_id == $marketer->id ? 'selected' : '' }}
                                                value="{{ $marketer->id }}">{{ $marketer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">@lang('Type')</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="1">@lang('Update Info Only')</option>
                                        <option value="2">@lang('Update Customer Commission')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Distributor Name')</label>
                                    <select name="distribution_id" id="distribution_id"
                                        class="form-select select2 distribution_id">
                                        <option value="">Select Distributor</option>
                                        @foreach ($distributors as $distributor)
                                            <option {{ old('distribution_id') == $distributor->id ? 'selected' : '' }}
                                                value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                        @endforeach
                                    </select>
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



    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($user->status == Status::USER_ACTIVE)
                            <span>@lang('Ban User')</span>
                        @else
                            <span>@lang('Unban User')</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.customers.status', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($user->status == Status::USER_ACTIVE)
                            <div class="form-group">
                                <label class="form-label">@lang('Reason')</label>
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                        @else
                            <p><span>@lang('Ban reason was'):</span></p>
                            <p>{{ $user->ban_reason }}</p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($user->status == Status::USER_ACTIVE)
                            <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                        @else
                            <button type="button" class="btn btn-dark"
                                data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
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

        })(jQuery);
    </script>
@endpush

@include('components.select2')

@extends('admin.layouts.app', ['title' => 'All Staff'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Staff
                <button type="button" class="btn btn-sm btn-outline-primary float-end cuModalBtn"
                    data-modal_title="@lang('Add New Staff')">
                    <i class="las la-plus"></i>@lang('Add New')
                </button>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-strip">
                    <thead>
                        <tr>
                            <th>@lang('Username')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Role')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allStaff as $staff)
                            <tr>
                                <td>{{ $staff->username }}</td>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->mobile }}</td>
                                <td>
                                    @if ($staff->role)
                                        {{ $staff->role->name }}
                                    @else
                                        @lang('Super Admin')
                                    @endif
                                </td>

                                <td>
                                    @php
                                        echo $staff->statusBadge;
                                    @endphp
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button type="button" class="cuModalBtn"
                                                    data-resource="{{ $staff }}"
                                                    data-modal_title="@lang('Update Staff')">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                            </li>

                                            @if ($staff->status)
                                                <li>
                                                    <button class="btn btn-sm confirmationBtn btn-outline--danger"
                                                        data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                        data-question="@lang('Are you sure to ban this staff?')" type="button">
                                                        <i class="las la-user-alt-slash"></i>@lang('Ban')
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <button class="btn btn-sm confirmationBtn btn-outline--success"
                                                        data-action="{{ route('admin.staff.status', $staff->id) }}"
                                                        data-question="@lang('Are you sure to unban this staff?')" type="button">
                                                        <i class="las la-user-check"></i>@lang('Unban')
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('admin.staff.login', $staff->id) }}" target="_blank">
                                                    <i class="las la-sign-in-alt"></i>@lang('Login')
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($allStaff->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($allStaff) }}

            </div>
        @endif
    </div>

    <x-confirmation-modal />

    <!-- Create Update Modal -->
    <div class="modal fade" id="cuModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.staff.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Username')</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Email')</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Mobile')</label>
                            <input type="text" class="form-control" name="mobile" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Role')</label>
                            <select name="role_id" class="form-select" required>
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-lebel">@lang('Password')</label>
                            <div class="input-group">
                                <input class="form-control" name="password" type="text" required>
                                <button class="input-group-text generatePassword" type="button">@lang('Generate')</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        (function($) {
            "use strict";
            $('.generatePassword').on('click', function() {
                $(this).siblings('[name=password]').val(generatePassword());
            });

            $('.cuModalBtn').on('click', function() {
                let passwordField = $('#cuModal').find($('[name=password]'));
                let label = passwordField.parents('.form-group mb-3').find('label')
                if ($(this).data('resource')) {
                    passwordField.removeAttr('required');
                    label.removeClass('required')
                } else {
                    passwordField.attr('required', 'required');
                    label.addClass('required')
                }
            });

            function generatePassword(length = 12) {
                let charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?/";
                let password = '';

                for (var i = 0, n = charset.length; i < length; ++i) {
                    password += charset.charAt(Math.floor(Math.random() * n));
                }

                return password
            }
        })(jQuery);
    </script>
@endpush

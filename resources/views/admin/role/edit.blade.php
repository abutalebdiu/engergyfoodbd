@extends('admin.layouts.app')
@section('title', 'Edit Role')
@section('panel')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.role.update', @$role->id) }}" method="post">
                @csrf
                <div class="gy-4">
                    <div class="my-4 row">
                        <div class="col-md-3">
                            <label for="name">
                                <h5>@lang('Name')</h5>
                            </label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control w-50"
                                value="{{ old('name', @$role->name) }}">
                        </div>
                        <hr class="my-4">
                    </div>
                    <div class="my-4 row">
                        <div class="col-md-3">
                            <label for="name">
                                <h5>@lang('Guard Name')</h5>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="guard_name" class="form-control w-50"
                                value="{{ old('guard_name', @$role->guard_name) }}">
                        </div>
                        <hr class="my-4">
                    </div>
                    <div class="my-3 row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="select_all" class="form-label">
                                    <h5>@lang('Permissions')</h5>
                                </label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="select_all" value="1"
                                        id="select_all">
                                    <label class="form-check-label" for="select_all">@lang('Select All')</label>
                                </div>
                            </div>
                            <div class="accordion" id="permissionsAccordion">
                                @foreach ($permissionGroups as $groupName => $permissions)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ Str::slug($groupName) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ Str::slug($groupName) }}" aria-expanded="false"
                                                aria-controls="collapse{{ Str::slug($groupName) }}">
                                                {{ Str::replaceLast('Controller', '', __($groupName)) }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ Str::slug($groupName) }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ Str::slug($groupName) }}"
                                            data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    @foreach ($permissions as $permission)
                                                        <div class="col-md-12 my-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="permissions[]"
                                                                    id="customCheck{{ $permission->id }}"
                                                                    value="{{ $permission->id }}"
                                                                    {{ in_array((int) $permission->id, $selectedPermissions) ? 'checked' : '' }} />
                                                                <label class="form-check-label"
                                                                    for="customCheck{{ $permission->id }}">
                                                                    {{ __($permission->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";


            // If all checkboxes are checked, then check the "select all" checkbox
            if ($('input[type="checkbox"]').length === $('input[type="checkbox"]:checked').length) {
                $("#select_all").prop('checked', true);
            } else {
                $("#select_all").prop('checked', false);
            }


        })(jQuery);


        $("#select_all").on('click', function() {
            $('input:checkbox').not(this).prop('checked', this.checked);

        });
    </script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Create Role')
@section('panel')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.role.store', @$role->id) }}" method="post">
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
                    <div class="row">
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
                                @foreach ($permissionGroups as $key => $permissionGroup)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $key }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}"
                                                aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                {{ Str::replaceLast('Controller', '', __($key)) }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $key }}"
                                            data-bs-parent="#permissionsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    @foreach ($permissionGroup as $permission)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="permissions[]" value="{{ $permission->id }}"
                                                                    id="customCheck{{ $permission->id }}">
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

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                            </div>
                        </div>
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
            @isset($permissions)
                $('input[name="permissions[]"]').val(@json($permissions));
            @endif
        })(jQuery);


        $("#select_all").on('click', function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endpush

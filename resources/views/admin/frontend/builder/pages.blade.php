@extends('admin.layouts.app', ['title' => __('Manage Pages')])
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Slug')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pdata as $k => $data)
                                    <tr>
                                        <td>{{ __($data->name) }}</td>
                                        <td>{{ __($data->slug) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.frontend.manage.section', $data->id) }}"><i
                                                                class="la la-pen"></i> @lang('Edit')</a>
                                                    </li>
                                                    @if ($data->is_default == Status::NO)
                                                        <li>
                                                            <button class="confirmationBtn"
                                                                data-action="{{ route('admin.frontend.manage.pages.delete', $data->id) }}"
                                                                data-question="@lang('Are you sure to remove this page?')">
                                                                <i class="las la-trash"></i> @lang('Delete')
                                                            </button>
                                                        </li>
                                                    @endif

                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>

    {{-- Add METHOD MODAL --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Page')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.frontend.manage.pages.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 form-group">
                            <label class="form-label"> @lang('Page Name')</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label"> @lang('Slug')</label>
                            <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline-primary addBtn"><i
            class="las la-plus me-1"></i>@lang('Add New')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addBtn').on('click', function() {
                var modal = $('#addModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

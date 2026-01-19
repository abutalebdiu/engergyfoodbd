@extends('admin.layouts.app', ['title' => @$title])
@section('panel')
    <div id="app">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <div class="mt-3 row justify-content-between">
                            <div class="col-md-7">
                                <h5>@lang('Language Keywords of') {{ __($lang->name) }}</h5>
                            </div>
                            <div class="mt-3 col-md-5 mt-md-0">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#addModal"
                                    class="btn btn-outline-primary float-end"><i class="fa fa-plus"></i>
                                    @lang('Add New Key') </button>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive--sm table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            @lang('Key')
                                        </th>
                                        <th>
                                            {{ __($lang->name) }}
                                        </th>

                                        <th class="w-85">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($json as $k => $language)
                                        <tr>
                                            <td class="white-space-wrap">{{ strLimit($k, 50) }}</td>
                                            <td class="text-left white-space-wrap">{{ strLimit($language, 50) }}</td>


                                            <td>

                                                <div class="btn-group">
                                                    <button data-bs-toggle="dropdown">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button data-bs-target="#editModal" data-bs-toggle="modal"
                                                                data-title="{{ $k }}"
                                                                data-key="{{ $k }}"
                                                                data-value="{{ $language }}" class="editModal">
                                                                <i class="bi bi-pencil"></i>@lang('Edit')
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button data-key="{{ $k }}"
                                                                data-value="{{ $language }}" data-bs-toggle="modal"
                                                                data-bs-target="#DelModal" class="deleteKey">
                                                                <i class="bi bi-trash"></i>@lang('Remove')
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($json->hasPages())
                        <div class="card-footer pagination-card-footer">
                            @php
                                echo paginateLinks($json);
                            @endphp
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel"> @lang('Add New')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.language.store.key', $lang->id) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3 form-group">
                                <label for="key" class="form-label">@lang('Key')</label>
                                <input type="text" class="form-control" id="key" name="key"
                                    value="{{ old('key') }}" required>

                            </div>
                            <div class="mb-3 form-group">
                                <label for="value" class="form-label">@lang('Value')</label>
                                <input type="text" class="form-control" id="value" name="value"
                                    value="{{ old('value') }}" required>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100"> @lang('Submit')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">@lang('Edit')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.language.update.key', $lang->id) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3 form-group">
                                <label for="inputName" class="form-title form-label"></label>
                                <input type="text" class="form-control" name="value" required>
                            </div>
                            <input type="hidden" name="key">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <!-- Modal for DELETE -->
        <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="DelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DelModalLabel"> @lang('Confirmation Alert!')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete this key from this language?')</p>
                    </div>
                    <form action="{{ route('admin.language.delete.key', $lang->id) }}" method="post">
                        @csrf
                        <input type="hidden" name="key">
                        <input type="hidden" name="value">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark"
                                data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn-primary">@lang('Yes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Import Modal --}}
    <div class="modal fade" id="importModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Import Keywords')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">@lang('Import From')</label>
                        <select class="form-control select_lang" required>
                            <option value="">@lang('Select One')</option>
                            <option value="999">@lang('System')</option>
                            @foreach ($list_lang as $data)
                                <option value="{{ $data->id }}"
                                    @if ($data->id == $lang->id) class="d-none" @endif>{{ __($data->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="button" class="btn btn-primary import_lang"> @lang('Import Now')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <div class="gap-3 d-flex">
        <div>

            <div class="flex-wrap gap-2 mb-2 d-flex">
                <x-search-form placeholder="Search keywords" />
                <button type="button" class="btn btn-primary importBtn"><i
                        class="la la-download"></i>@lang('Import Keywords')</button>
                <a href="{{ route('admin.language.manage') }}" class="btn btn-primary"><i
                        class="bi bi-arrow-clockwise"></i>@lang('Back')</a>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.deleteKey', function() {
                var modal = $('#DelModal');
                modal.find('input[name=key]').val($(this).data('key'));
                modal.find('input[name=value]').val($(this).data('value'));
            });

            $(document).on('click', '.editModal', function() {


                var modal = $('#editModal');

                modal.find('.form-title').text($(this).data('title'));
                modal.find('input[name=key]').val($(this).data('key'));
                modal.find('input[name=value]').val($(this).data('value'));
            });


            $(document).on('click', '.importBtn', function() {
                $('#importModal').modal('show');
            });
            $(document).on('click', '.import_lang', function(e) {
                var id = $('.select_lang').val();

                if (id == '') {
                    notify('error', 'Invalide selection');

                    return 0;
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ route('admin.language.import.lang') }}",
                        data: {
                            id: id,
                            toLangid: "{{ $lang->id }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data == 'success') {
                                notify('success', 'Import Data Successfully');
                                window.location.href = "{{ url()->current() }}"
                            }
                        }
                    });
                }

            });


        })(jQuery);
    </script>
@endpush

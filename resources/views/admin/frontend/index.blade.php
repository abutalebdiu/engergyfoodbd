@extends('admin.layouts.app',['title'=>$section->name])

@section('panel')
    @if (@$section->content)
        <div class="row">
            <div class="col-lg-12 col-md-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.frontend.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="content">
                            <div class="row">
                                @php
                                    $imgCount = 0;
                                @endphp
                                @foreach ($section->content as $k => $item)
                                    @if ($k == 'images')
                                        @php
                                            $imgCount = collect($item)->count();
                                        @endphp
                                        @foreach ($item as $imgKey => $image)
                                            <div class="col-md-4">
                                                <input type="hidden" name="has_image" value="1">
                                                <div class="mb-3 form-group">
                                                    <label class="required form-label">{{ __(keyToTitle(@$imgKey)) }}</label>
                                                    <x-image-uploader class="w-100" name="image_input[{{ @$imgKey }}]" :imagePath="getImage('assets/images/frontend/' . $key . '/' . @$content->data_values->$imgKey, @$section->content->images->$imgKey->size)" id="image-upload-input{{ $loop->index }}" :size="$section->content->images->$imgKey->size" :required="false" />
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="@if ($imgCount > 1) col-md-12 @else col-md-8 @endif">
                                            @push('divend')
                                            </div>
                                        @endpush
                                    @else
                                        @if ($k != 'images')
                                            @if ($item == 'icon')
                                                <div class="col-md-12">
                                                    <div class="form-group ">
                                                        <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control iconPicker icon" autocomplete="off" name="{{ $k }}" value="{{ @$content->data_values->$k }}" required>
                                                            <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($item == 'textarea')
                                                <div class="col-md-12">
                                                    <div class="mb-3 form-group">
                                                        <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                                        <textarea rows="10" class="form-control" name="{{ $k }}" required>{{ @$content->data_values->$k }}</textarea>
                                                    </div>
                                                </div>
                                            @elseif($item == 'textarea-nic')
                                                <div class="col-md-12">
                                                    <div class="mb-3 form-group">
                                                        <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                                        <textarea rows="10" class="form-control nicEdit" name="{{ $k }}">{{ @$content->data_values->$k }}</textarea>
                                                    </div>
                                                </div>
                                            @elseif($k == 'select')
                                                @php
                                                    $selectName = $item->name;
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="mb-3 form-group">
                                                        <label class="form-label">{{ __(keyToTitle(@$selectName)) }}</label>
                                                        <select class="form-control" name="{{ @$selectName }}">
                                                            @foreach ($item->options as $selectItemKey => $selectOption)
                                                                <option value="{{ $selectItemKey }}" @if (@$content->data_values->$selectName == $selectItemKey) selected @endif>{{ $selectOption }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    <div class="mb-3 form-group">
                                                        <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                                        <input type="text" class="form-control" name="{{ $k }}" value="{{ @$content->data_values->$k }}" required />
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                @stack('divend')
                            </div>
                            <div class="mb-3 form-group">
                                <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (@$section->element)
        <div class="flex-wrap mb-3 d-flex justify-content-end">
            <div class="d-inline">
                <div class="input-group justify-content-end">
                    <input type="text" name="search_table" class="bg-white form-control" placeholder="@lang('Search')...">
                    <button class="btn btn-primary input-group-text"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>@lang('SL')</th>
                                        @if (@$section->element->images)
                                            <th>@lang('Image')</th>
                                        @endif
                                        @foreach ($section->element as $k => $type)
                                            @if ($k != 'modal')
                                                @if ($type == 'text' || $type == 'icon')
                                                    <th>{{ __(keyToTitle($k)) }}</th>
                                                @elseif($k == 'select')
                                                    <th>{{ keyToTitle(@$section->element->$k->name) }}</th>
                                                @endif
                                            @endif
                                        @endforeach
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @forelse($elements as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @if (@$section->element->images)
                                                @php $firstKey = collect($section->element->images)->keys()[0]; @endphp
                                                <td>
                                                    <div class="customer-details d-block">
                                                        <a href="javascript:void(0)" class="thumb">
                                                            <img src="{{ getImage('assets/images/frontend/' . $key . '/' . @$data->data_values->$firstKey, @$section->element->images->$firstKey->size) }}" alt="@lang('image')">
                                                        </a>
                                                    </div>
                                                </td>
                                            @endif
                                            @foreach ($section->element as $k => $type)
                                                @if ($k != 'modal')
                                                    @if ($type == 'text' || $type == 'icon')
                                                        @if ($type == 'icon')
                                                            <td>@php echo @$data->data_values->$k; @endphp</td>
                                                        @else
                                                            <td>{{ __(@$data->data_values->$k) }}</td>
                                                        @endif
                                                    @elseif($k == 'select')
                                                        @php
                                                            $dataVal = @$section->element->$k->name;
                                                        @endphp
                                                        <td>{{ @$data->data_values->$dataVal }}</td>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <td>



                                                <div class="btn-group">
                                                <button data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if ($section->element->modal)
                                                        @php
                                                            $images = [];
                                                            if (@$section->element->images) {
                                                                foreach ($section->element->images as $imgKey => $imgs) {
                                                                    $images[] = getImage('assets/images/frontend/' . $key . '/' . @$data->data_values->$imgKey, @$section->element->images->$imgKey->size);
                                                                }
                                                            }
                                                        @endphp
                                                       <li>
                                                        <button class="updateBtn" data-id="{{ $data->id }}" data-all="{{ json_encode($data->data_values) }}" @if (@$section->element->images) data-images="{{ json_encode($images) }}" @endif>
                                                            <i class="la la-pencil-alt"></i> @lang('Edit')
                                                        </button>
                                                       </li>
                                                    @else
                                                    <li>
                                                        <a href="{{ route('admin.frontend.sections.element', [$key, $data->id]) }}"><i class="la la-pencil-alt"></i> @lang('Edit')</a>

                                                    </li>
                                                    @endif
                                                    <li>
                                                        <button class="confirmationBtn" data-action="{{ route('admin.frontend.remove', $data->id) }}" data-question="@lang('Are you sure to remove this item?')"><i class="la la-trash"></i> @lang('Remove')</button>
                                                    </li>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add METHOD MODAL --}}
        <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Add New') {{ __(keyToTitle($key)) }} @lang('Item')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <div class="modal-body">
                            @foreach ($section->element as $k => $type)
                                @if ($k != 'modal')
                                    @if ($type == 'icon')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control iconPicker icon" autocomplete="off" name="{{ $k }}" required>
                                                <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle(@$section->element->$k->name) }}</label>
                                            <select class="form-control" name="{{ @$section->element->$k->name }}">
                                                @foreach ($section->element->$k->options as $selectKey => $options)
                                                    <option value="{{ $selectKey }}">{{ $options }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif($k == 'images')
                                        @foreach ($type as $imgKey => $image)
                                            <input type="hidden" name="has_image" value="1">
                                            <div class="mb-3 form-group">
                                                <label class="form-label">{{ __(keyToTitle($k)) }}</label>

                                                <x-image-uploader class="w-100" name="image_input[{{ @$imgKey }}]" :imagePath="getImage('assets/images/frontend/' . $key . '/' . @$content->data_values->$imgKey, @$section->element->images->$imgKey->size)" id="addImage{{ $loop->index }}" :size="$section->element->images->$imgKey->size" />
                                            </div>
                                        @endforeach
                                    @elseif($type == 'textarea')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                            <textarea rows="4" class="form-control" name="{{ $k }}" required></textarea>
                                        </div>
                                    @elseif($type == 'textarea-nic')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                            <textarea rows="4" class="form-control nicEdit" name="{{ $k }}"></textarea>
                                        </div>
                                    @else
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ __(keyToTitle($k)) }}</label>
                                            <input type="text" class="form-control" name="{{ $k }}" required />
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Update METHOD MODAL --}}
        <div id="updateBtn" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Update') {{ __(keyToTitle($key)) }} @lang('Item')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" class="edit-route" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            @foreach ($section->element as $k => $type)
                                @if ($k != 'modal')
                                    @if ($type == 'icon')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle($k) }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control iconPicker icon" autocomplete="off" name="{{ $k }}" required>
                                                <span class="input-group-text input-group-addon" data-icon="las la-home" role="iconpicker"></span>
                                            </div>
                                        </div>
                                    @elseif($k == 'select')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle(@$section->element->$k->name) }}</label>
                                            <select class="form-control" name="{{ @$section->element->$k->name }}">
                                                @foreach ($section->element->$k->options as $selectKey => $options)
                                                    <option value="{{ $selectKey }}">{{ $options }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif($k == 'images')
                                        @foreach ($type as $imgKey => $image)
                                            <input type="hidden" name="has_image" value="1">
                                            <div class="mb-3 form-group">
                                                <label class="form-label">{{ __(keyToTitle($k)) }}</label>

                                                <x-image-uploader class="w-100" :imagePath="getImage('', $section->element->images->$imgKey->size)" name="image_input[{{ @$imgKey }}]" id="updateImage{{ $loop->index }}" :size="$section->element->images->$imgKey->size" :required="false" />

                                            </div>
                                        @endforeach
                                    @elseif($type == 'textarea')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle($k) }}</label>
                                            <textarea rows="4" class="form-control" name="{{ $k }}" required></textarea>
                                        </div>
                                    @elseif($type == 'textarea-nic')
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle($k) }}</label>
                                            <textarea rows="4" class="form-control nicEdit" name="{{ $k }}"></textarea>
                                        </div>
                                    @else
                                        <div class="mb-3 form-group">
                                            <label class="form-label">{{ keyToTitle($k) }}</label>
                                            <input type="text" class="form-control" name="{{ $k }}" required />
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    {{-- if section element end --}}

    <x-confirmation-modal />

@endsection

@push('breadcrumb-plugins')
    <div class="flex-wrap gap-2 d-flex justify-content-end align-items-center">
        @if (@$section->element)
            @if ($section->element->modal)
                <a href="javascript:void(0)" class="btn btn-outline-primary me-1 addBtn"><i class="las la-plus"></i>@lang('Add New')</a>
            @else
                <a href="{{ route('admin.frontend.sections.element', $key) }}" class="btn btn-sm btn-outline-primary"><i class="las la-plus"></i>@lang('Add New')</a>
            @endif
        @endif
    </div>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addBtn').on('click', function() {
                var modal = $('#addModal');
                modal.modal('show');
            });

            $('.updateBtn').on('click', function() {
                var modal = $('#updateBtn');
                modal.find('input[name=id]').val($(this).data('id'));

                var obj = $(this).data('all');
                var images = $(this).data('images');

                var imagePreviews = modal.find('.image-upload-preview');


                if (images) {

                    for (var i = 0; i < images.length; i++) {

                        var imgloc = images[i];

                        console.log(imgloc);
                        $(imagePreviews[i]).css("background-image", "url(" + imgloc + ")");
                    }
                }
                $.each(obj, function(index, value) {
                    modal.find('[name=' + index + ']').val(value);
                });
                modal.modal('show');
            });

            $('#updateBtn').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });
            $('#addModal').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });
            $('.iconPicker').iconpicker().on('iconpickerSelected', function(e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });
        })(jQuery);
    </script>
@endpush

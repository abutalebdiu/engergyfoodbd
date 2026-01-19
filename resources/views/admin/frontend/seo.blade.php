@extends('admin.layouts.app',['title'=> 'SEO Configuration'])

@section('panel')
    <div class="row">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.frontend.sections.content', 'seo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="data">
                        <input type="hidden" name="seo_image" value="1">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('SEO Image')</label>
                                    <x-image-uploader class="w-100" name="image_input" :imagePath="getImage(getFilePath('seo') . '/' . @$seo->data_values->image, getFileSize('seo'))" :size="getFileSize('seo')" :required="false" />

                                </div>
                            </div>

                            <div class="mt-4 col-xl-8 mt-xl-0">
                                <div class="mb-3 form-group select2-parent position-relative">
                                    <label class="form-label">@lang('Meta Keywords')</label>
                                    <small class="mt-2 ms-2 ">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma')) @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                    <select name="keywords[]" class="form-control select2-auto-tokenize" multiple="multiple" required>
                                        @if (@$seo->data_values->keywords)
                                            @foreach ($seo->data_values->keywords as $option)
                                                <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Meta Description')</label>
                                    <textarea name="description" rows="3" class="form-control" required>{{ @$seo->data_values->description }}</textarea>
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Social Title')</label>
                                    <input type="text" class="form-control" name="social_title" value="{{ @$seo->data_values->social_title }}" required />
                                </div>
                                <div class="mb-3 form-group">
                                    <label class="form-label">@lang('Social Description')</label>
                                    <textarea name="social_description" rows="3" class="form-control" required>{{ @$seo->data_values->social_description }}</textarea>
                                </div>
                                <div class="mb-3 form-group">
                                    <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.select2-auto-tokenize').select2({
                dropdownParent: $('.select2-parent'),
                tags: true,
                tokenSeparators: [',']
            });
        })(jQuery);
    </script>
@endpush

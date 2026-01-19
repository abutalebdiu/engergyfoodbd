@extends('web.layouts.master')
@section('content')
<form action="{{ route('support.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="form-group col-md-6 mb-3">
            <label class="form-label">@lang('Subject')</label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control "
                required>
        </div>

        <div class="form-group col-md-6 mb-3">

            <div class="file-upload">
                <label class="form-label">@lang('Attachments')</label>
                <input type="file" name="attachments[]" id="inputAttachments"
                    class="form-control  mb-2" />
                <div id="fileUploadsContainer"></div>
                <p class="ticket-attachments-message text-muted">
                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                    .@lang('pdf'), .@lang('doc'), .@lang('docx')
                </p>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-base btn-sm  addFile">
                    <i class="fa fa-plus"></i> @lang('Add New File')
                </button>
            </div>
        </div>

        <div class="col-12 form-group mb-3">
            <label class="form-label">@lang('Message')</label>
            <textarea name="message" id="inputMessage" rows="6" class="form-control " required>{{ old('message') }}</textarea>
        </div>
    </div>

    <button class="btn btn-base w-100" type="submit">@lang('Submit')</button>
</form>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control " required />
                        <button type="button" class="input-group-text btn btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush

@push('title')
    <h5>@lang('Open Support')</h5>
@endpush

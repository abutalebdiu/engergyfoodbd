@extends('admin.layouts.app',['title'=> 'General Setting'])
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6 col-sm-6">
                                <div class="form-group ">
                                    <label class="form-label"> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{gs('site_name')}}">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 col-sm-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{gs('cur_text')}}">
                                </div>
                            </div>
                            <div class="mb-3 col-md-12 col-sm-12">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Address')</label>
                                    <input class="form-control" type="text" name="address" required value="{{gs('address')}}">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 col-sm-6">
                                <div class="form-group ">
                                    <label class="form-label"> @lang('Phone')</label>
                                    <input class="form-control" type="text" name="phone" required value="{{gs('phone')}}">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 col-sm-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Mobile')</label>
                                    <input class="form-control" type="text" name="mobile" required value="{{gs('mobile')}}">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 col-sm-6">
                                <div class="form-group ">
                                    <label class="form-label">@lang('Currency Symbol')</label>
                                    <input class="form-control" type="text" name="cur_sym" required value="{{gs('cur_sym')}}">
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .select2-container {
        z-index: 0 !important;
    }
</style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function (color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function () {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('select[name=timezone]').val("{{ $currentTimezone }}").select2();
            $('.select2-basic').select2({
                dropdownParent:$('.card-body')
            });
        })(jQuery);

    </script>
@endpush


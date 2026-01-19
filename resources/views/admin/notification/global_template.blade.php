@extends('admin.layouts.app',['title'=> 'Global Template for Notification'])
@section('panel')
<div class="row">
	<div class="col-md-12">
        <div class="overflow-hidden card">
            <div class="card-body">
                <div class="table-responsive table-responsive--sm">
                    <table class="table">
                        <thead class="table-light">
                        <tr>
                            <th>@lang('Short Code') </th>
                            <th>@lang('Description')</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        {{-- blade-formatter-disable --}}
                        <tr>
                            <td><span class="short-codes">@{{fullname}}</span></td>
                            <td>@lang('Full Name of User')</td>
                        </tr>
                        <tr>
                            <td><span class="short-codes">@{{username}}</span></td>
                            <td>@lang('Username of User')</td>
                        </tr>
                        <tr>
                            <td><span class="short-codes">@{{message}}</span></td>
                            <td>@lang('Message')</td>
                        </tr>
                        {{-- blade-formatter-enable --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <h6 class="mt-4 mb-2">@lang('Global Short Codes')</h6>
        <div class="overflow-hidden card">
            <div class="card-body">
                <div class="table-responsive table-responsive--sm">
                    <table class="table">
                        <thead class="table-light">
                        <tr>
                            <th>@lang('Short Code') </th>
                            <th>@lang('Description')</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @foreach(gs('global_shortcodes') as $shortCode => $codeDetails)
                        <tr>
                            <td><span class="short-codes">@{{@php echo $shortCode @endphp}}</span></td>
                            <td>{{ __($codeDetails) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="mt-5 card">
            <div class="card-body">
                <form action="{{ route('admin.setting.notification.global.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label class="form-label">@lang('Email Sent From') </label>
                                <input type="text" class="form-control " placeholder="@lang('Email address')" name="email_from" value="{{ gs('email_from') }}" required/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label class="form-label">@lang('Email Body') </label>
                                <textarea name="email_template" rows="10" class="form-control nicEdit" placeholder="@lang('Your email template')">{{ gs('email_template') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label class="form-label">@lang('SMS Sent From') </label>
                                <input class="form-control" placeholder="@lang('SMS Sent From')" name="sms_from" value="{{ gs('sms_from') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3 form-group">
                                <label class="form-label">@lang('SMS Body') </label>
                                <textarea class="form-control" rows="4" placeholder="@lang('SMS Body')" name="sms_body" required>{{ gs('sms_body') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 btn-primary h-45">@lang('Submit')</button>
                </form>
            </div>
        </div><!-- card end -->
    </div>

</div>
@endsection

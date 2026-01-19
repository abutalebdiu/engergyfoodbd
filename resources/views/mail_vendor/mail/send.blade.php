@extends('admin.layouts.app', ['title' => 'SMS Send'])
@section('panel')
    <div class="container-fluid">
        <form action="{{ route('admin.mail.sendmail') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-xl-12">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-warning">{{ session('error') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="py-3 col-12">
                                    <label for="" class="form-label">@lang('Sender Email') </label>
                                    <select name="domain" id="domain" class="form-control" required>
                                        <option value="">@lang('Select Email')</option>
                                        @foreach ($domainconfigs as $domainconfig)
                                            <option value="{{ $domainconfig->id }}">{{ $domainconfig->domain }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="py-3 col-12">
                                    <label for="" class="form-label">@lang('Email Address')<span
                                            class="text-danger">*</span></label>
                                    <textarea name="email_address" id="email" class="form-control" rows="3" placeholder=""></textarea>
                                </div>
                                <div class="py-3 col-12">
                                    <label for="" class="form-label">@lang('Email Subject')<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control">
                                </div>

                                <div class="py-3 col-12">
                                    <label for="" class="form-label">@lang('Mail Body (Message)') <span
                                            class="text-danger">*</span></label>
                                    <textarea name="message" id="message" class="form-control message" rows="5"></textarea>
                                    <br>
                                    {{-- <p>Code : <span class="code"></span></p> --}}
                                </div>
                                <div class="py-3 col-12">
                                    <label for="" class="form-label">@lang('Attachment') (@lang('User can add multiple File'))
                                        (@lang('Optional'))</label>
                                    <input type="file" name="attachment[]" class="form-control" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="px-4 btn btn-primary"> <i class="bi bi-send"></i>
                                        @lang('Send')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

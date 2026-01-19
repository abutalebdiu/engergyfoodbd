@extends('admin.layouts.app', ['title' => 'Mail Send'])
@section('panel')
    <form action="{{ route('admin.mail.send.group.mail') }}" method="post" enctype="multipart/form-data">
        @csrf
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif
        <div class="mt-2 card">
            <div class="card-header">
                <h5 class="mb-0">
                    Mail Send Buyer/Supplier
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="py-3 col-12">
                        <label for="" class="form-label">@lang('Sender Email')</label>
                        <select name="domain" id="domain" class="form-control" required>
                            <option value="">@lang('Select Email')</option>
                            @foreach ($domainconfigs as $domainconfig)
                                <option value="{{ $domainconfig->id }}">{{ $domainconfig->domain }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="py-3 col-12">
                        <label for="" class="form-label">@lang('Receiver')<span class="text-danger">*</span></label>
                        <select name="receiver" id="receiver" class="form-control" required>
                            <option value="">@lang('Select Receiver')</option>
                            <option value="buyer">Buyer</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                    <div class="py-3 col-12">
                        <label for="" class="form-label">@lang('Email Subject')<span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="py-3 col-12">
                        <label for="" class="form-label">@lang('Mail Body (Message)') <span
                                class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control message" rows="5"></textarea>
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
    </form>
@endsection

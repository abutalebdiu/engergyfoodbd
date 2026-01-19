@extends('admin.layouts.app', ['title' => 'Send SMS'])
@section('panel')
    <form action="{{ route('admin.sms.send.general.message') }}" method="post" enctype="multipart/form-data">
        @csrf

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Send SMS</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="py-3 col-12">
                        <label for="" class="form-label">Select Provider <span class="text-danger">*</span></label>
                        <select name="sms_config_id" id="sms_config_id" class="form-control" required>
                            <option value="">Select provider</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('provider')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="py-3 col-12">
                        <label for="" class="form-label">Mobile Numbers <span class="text-danger">*</span></label>
                        <textarea name="mobile_no" id="mobile_no" class="form-control" rows="3"
                            placeholder="01xxxxxxxxx,01xxxxxxxxx,01xxxxxxxxx">{{ old('mobile_no') }}</textarea>
                        @error('phone')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="py-3 col-12">
                        <label for="" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control message" rows="3"></textarea>
                        @error('message')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <br>
                        <br>
                        <ul id="sms-counter" class="list-group">
                            <li class="list-group-item">Encoding: <span class="encoding"></span></li>
                            <li class="list-group-item">Length: <span class="length"></span></li>
                            <li class="list-group-item">Messages: <span class="messages"></span></li>
                            <li class="list-group-item">Per Message: <span class="per_message"></span></li>
                            <li class="list-group-item">Remaining: <span class="remaining"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="px-4 btn btn-primary"> <i class="bi bi-send"></i>
                            Send</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('components.smscount')
@endsection

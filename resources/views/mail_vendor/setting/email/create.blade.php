@extends('admin.layouts.app', ['title' => 'Mail Config'])
@section('panel')
    <div class="container">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div>
                <h6 class="m-0">{{ __('Email Config') }}</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.setting.storeEmail') }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="name">{{ __('Title') }}</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Configuration Email') }}</label>
                                <input type="email" name="domain" value="{{ old('domain') }}" class="form-control">
                                @error('domain')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Mailer') }}</label>
                                <input type="text" name="mail" value="{{ old('mail') }}" class="form-control">
                                @error('mail')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Host') }}</label>
                                <input type="text" name="host" value="{{ old('host') }}" class="form-control">
                                @error('host')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Port') }}</label>
                                <input type="text" name="port" value="{{ old('port') }}" class="form-control">
                                @error('port')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('User Name') }}</label>
                                <input type="text" name="user_name" value="{{ old('user_name') }}" class="form-control">
                                @error('user_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Password') }}</label>
                                <input type="text" name="password" value="{{ old('password') }}" class="form-control">
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Encryption') }}</label>
                                <input type="text" name="encryption" value="{{ old('encryption') }}" class="form-control">
                                @error('encryption')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="py-2 col-sm-6">
                            <div class="form-group">
                                <label for="title">{{ __('Address') }}</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                @error('address')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option {{ old('status') == '1' ? 'selected' : '' }} value="1">{{__('Active')}}</option>
                                    <option {{ old('status') == '0' ? 'selected' : '' }} value="0">{{__('Inactive')}}</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3 col-sm-12">
                            <a href="{{ route('admin.setting.email') }}" class="px-3 btn btn-warning btn-sm">{{__('Cancel')}}</a>
                            <button type="submit" class="px-3 btn btn-primary btn-sm">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

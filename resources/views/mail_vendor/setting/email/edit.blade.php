@extends('admin.layouts.app', ['title' => 'Mail Config'])
@section('panel')
    <div class="container">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div>
                <h6 class="m-0">Edit Mail Config</h6>
            </div>
        </div>
        <div class="card">
            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">{{session('error')}}</div>
            @endif
            <div class="card-body">
                <form action="{{ route('admin.setting.store.email', @$email->id) }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" name="title" value="{{ @$email->title }}" class="form-control" required>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($email->config)
                            @foreach (json_decode($email->config, true) as $key => $config)
                                <div class="py-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="{{ $key }}">{{ ucwords(str_replace("_", " ", $key)) }}</label>
                                        <input type="text" name="{{ $key }}" value="{{ $config }}" class="form-control" required>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="py-2 col-sm-12">
                            <div class="form-group">
                                <label for="status">Group</label>
                                <select name="status" class="form-control">
                                    <option {{ $email->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $email->status == '0' ? 'selected' : '' }} value="0">Inactive</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 col-sm-12">
                            <a href="{{ route('admin.setting.email') }}" class="px-3 btn btn-warning btn-sm">Cancel</a>
                            <button type="submit" class="px-3 btn btn-primary btn-sm">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

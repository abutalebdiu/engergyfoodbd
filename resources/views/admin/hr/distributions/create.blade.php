@extends('admin.layouts.app', ['title' => 'Add New Distributor'])

@section('panel')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm bg-white">
                    <div class="card-header">
                        <h4 class="mb-0">Add Distributor</h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.distribution.store') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-12">
                                    <input type="submit" class="btn btn-primary float-start mt-3"
                                        onClick="this.form.submit(); this.disabled=true; this.value='সাবমিট হচ্ছে'; "
                                        value="@lang('Submit')">
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

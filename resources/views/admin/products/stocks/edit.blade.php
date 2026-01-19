@extends('admin.layouts.app', ['title' => 'Edit Stock'])
@section('panel')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.stock.update', $stock->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="pb-3 col-md-4">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Name')</label>
                                    <input class="form-control" type="text" name="name" required
                                        value="{{ $stock->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <a href="{{ route('admin.stock.index') }}"
                                    class="btn btn-outline-info mt-4 float-start">Back</a>
                                <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

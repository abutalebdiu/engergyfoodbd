@extends('admin.layouts.app', ['title' => 'Add New Liabilities'])
@section('panel')
    <form action="{{ route('admin.liabilitie.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Add New Liabilities')
                    <a href="{{ route('admin.liabilitie.index') }}" class="btn btn-primary btn-sm float-end"> <i
                            class="fa fa-arrow-left"></i> @lang('Liabilitie List')</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="pb-3 col-12 col-md-9">
                        <div class="form-group">
                            <label class="form-label text-capitalize">@lang('Liabilitie Name')</label>
                            <input type="text" name="name"  value="{{ old('name') }}"  class="form-control" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Amount')</label>
                            <input type="text" name="amount" value="{{ old('amount') }}" id="amount"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-12 mb-3">
                        <div class="form-group">
                            <label for="" class="form-label">@lang('Description')</label>
                            <textarea name="note" rows="4" class="form-control">{{ old('note') }}</textarea>
                        </div>
                    </div>                
 

                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <a href="{{ route('admin.asset.index') }}" class="btn btn-outline-info mt-4 float-start">Back</a>
                        <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

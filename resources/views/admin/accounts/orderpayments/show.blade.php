@extends('admin.layouts.app', ['title' => 'Customer Order Payment Receive Show'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Customer Order Payment Receive Show
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary btn-sm float-end"> <i class="fa fa-list"></i>
                    @lang('Back to Order Payment List')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
               
            </div>
        </div>
    </div>
@endsection

@include('components.select2')

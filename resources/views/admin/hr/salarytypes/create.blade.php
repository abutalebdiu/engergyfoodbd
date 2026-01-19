@extends('admin.layouts.app',['title'=>'Add New Salary Type'])
@section('panel')
     
        <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
            <div>
                <h4>Add New Salary Type</h4>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.salarytype.index') }}" type="button" class="btn btn-primary btn-sm"> <i
                        class="bi bi-arrow-counterclockwise"></i> Back To Salary Type list</a>
            </div>
        </div>
        <!--breadcrumb-->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.salarytype.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-sm-7 py-2">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div> 
                        <div class="col-sm-12  mt-3">
                            <a href="{{ route('admin.salarytype.index') }}" class="btn btn-warning btn-sm px-3">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm px-3">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
 
@endsection

@extends('admin.layouts.app', ['title' => 'Salary Type List'])
@section('panel')
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Salary Type List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.salarytype.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Add New Salary Type</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salarytypes as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div class="table-actions d-flex align-items-center gap-3 fs-6">
                                        <a href="{{ route('admin.salarytype.edit', $item->id) }}" class="text-warning"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i
                                                class="bi bi-pencil-fill"></i></a>
                                        <a href="javascript:;" class="text-danger" onclick="deleteItem({{ $item->id }})"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i
                                                class="bi bi-trash-fill"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection

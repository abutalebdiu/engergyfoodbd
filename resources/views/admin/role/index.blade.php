@extends('admin.layouts.app')
@section('title', 'Role List')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Role List</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.role.create') }}" class="mb-1 btn btn-primary btn-sm float-end"
                        id="create-new-class"><i class="fa fa-plus"></i>Add Role </a>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Sl.</th>
                                <th class="text-nowrap">Name</th>
                                <th class="text-nowrap">Guard</th>
                                <th class="text-nowrap">Permission</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->guard_name }}</td>
                                    <td>({{ $role->permission->count() }})</td>
                                    <td>
                                        {{-- @if ($role->name != 'Admin') --}}
                                        <a href="{{ route('admin.role.edit', $role->id) }}"
                                            class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                        {{-- @endif --}}

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app', ['title' => 'Distribution Details'])

@section('panel')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm bg-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Distribution Details</h4>
                    <a href="{{ route('admin.distribution.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $distribution->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $distribution->name }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $distribution->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $distribution->email }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $distribution->address }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="btn btn-{{ $distribution->status == 'Active' ? 'success' : 'danger' }} btn-sm">
                                    {{ $distribution->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $distribution->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $distribution->updated_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

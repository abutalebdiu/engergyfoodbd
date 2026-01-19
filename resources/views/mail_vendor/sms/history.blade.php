@extends('admin.layouts.app', ['title' => 'History'])
@section('panel')
    <div class="container-fluid">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div>
                <h6 class="m-0">SMS History List</h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SMS Provider</th>
                                <th>Mobile</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($smshistories as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->smsconfig)->name }}</td>
                                    <td>{{ $item->mobile }}</td>
                                    <td>{{ $item->message }}</td>
                                    <td>{{ $item->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

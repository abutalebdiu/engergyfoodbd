@extends('admin.layouts.app', ['title' => 'Category List'])
@section('panel')
    <div class="container-fluid">
        <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
            <div class="ms-auto">
                <a href="{{ route('admin.category.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                        class="bi bi-plus-circle"></i> @lang('Create New Category')</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Domain Name')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Note')</th>
                                <th>@lang('Config')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($domainconfigs as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->domain }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td>
                                          {{ $item->config }}
                                    </td>

                                    <td>
                                        <div class="gap-3 table-actions d-flex align-items-center fs-6">
                                            <a href="{{ route('admin.domainconfig.edit', $item->id) }}"
                                                class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="javascript:;" class="text-danger"
                                                onclick="deleteItem({{ $item->id }})" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Delete"><i
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
    </div>
@endsection

@push('delete')
    <form method="POST" id="deleteForm">
        @csrf
        @method('delete')
    </form>
@endpush

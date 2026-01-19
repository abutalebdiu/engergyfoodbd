@extends('admin.layouts.app', ['title' => 'Item Brand List'])
@section('panel')
    <!--breadcrumb-->
    <div class="pb-2 mb-2 page-breadcrumb d-flex align-items-center border-bottom">
        <div>
            <h6 class="m-0">Item Brand List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.items.itemBrand.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> Add New </a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $item)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $item->name }} </td>

                                <td> <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.items.itemBrand.create', $item->id) }}"
                                                    class="dropdown-item"><i class="bi bi-pencil-fill"></i>Edit</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="deleteItem({{ $item->id }})"
                                                    class="text-danger dropdown-item"><i class="bi bi-trash-fill"></i>
                                                    Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">No Data Found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>


    <form action="" method="post" id="deleteForm">
        @csrf
        @method('delete')
        <input type="submit" value="delete" style="display: none">
    </form>
@endsection



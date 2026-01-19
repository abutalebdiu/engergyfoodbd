@extends('admin.layouts.app', ['title' => 'Category list'])
@section('panel')
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Product Category </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-4 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('admin.productcategory.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" placeholder="Category name"
                                        required value="{{ old('name') }}">
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Add Category</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>@lang('SL')</th>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productcategories as $item)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
                                                <td> <span
                                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3 fs-6">
                                                        <a href="javascript:;" class="text-primary d-none"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="" data-bs-original-title="View detail"
                                                            aria-label="Views"><i class="bi bi-eye-fill"></i></a>
                                                        <a href="{{ route('admin.productcategory.edit', $item->id) }}"
                                                            class="text-warning" data-bs-toggle="tooltip"
                                                            data-bs-placement="bottom" title=""
                                                            data-bs-original-title="Edit info" aria-label="Edit"><i
                                                                class="bi bi-pencil-fill"></i></a>
                                                        <a href="javascript:;" class="text-danger d-none"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i class="bi bi-trash-fill"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
@endsection

@extends('admin.layouts.app', ['title' => 'Products'])
@section('panel')
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Products </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-4 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('admin.product.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" placeholder="Product name"
                                        required value="{{ old('name') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id">
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Images</label>
                                    <div>
                                        <x-image-uploader name="image" class="w-100" type="product_image"
                                            :showSizeFileType=true />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" cols="3" placeholder="Product Description"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Add Product</button>
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
                                <table class="table align-middle table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>@lang('SL')</th>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Image')</th>
                                            <th>@lang('Category')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $item)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
                                                <td>
                                                    <div
                                                        class="gap-2 d-md-flex align-items-center justify-content-end justify-content-lg-start">
                                                        <div class="avatar avatar--sm">
                                                            <img src="{{ getImage(getFilePath('product_image') . '/' . $item->image, getFileSize('product_image')) }}"
                                                                alt="@lang('Image')">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ optional($item->category)->name }}</td>
                                                <td> <span
                                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3 fs-6">
                                                        <a href="javascript:;" class="text-primary d-none"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="" data-bs-original-title="View detail"
                                                            aria-label="Views"><i class="bi bi-eye-fill"></i></a>
                                                        <a href="{{ route('admin.product.edit', $item->id) }}"
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

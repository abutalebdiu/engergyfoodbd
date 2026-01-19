@extends('admin.layouts.app', ['title' => 'Products'])
@section('panel')
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Products </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('admin.product.update',$product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" placeholder="Product name"
                                        required value="{{$product->name }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id">
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option {{ $product->category_id == $category->id ? "selected" : "" }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Images</label>
                                    <div>
                                        <x-image-uploader name="image" image="{{ $product->image }}" class="w-100" type="product_image"
                                            :showSizeFileType=true />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" cols="3" placeholder="Product Description">{{ $product->description }}</textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Update Product</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div><!--end row-->
        </div>
    </div>
@endsection

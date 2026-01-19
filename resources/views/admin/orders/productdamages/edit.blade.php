@extends('admin.layouts.app', ['title' => 'Edit Damage Product'])
@section('panel')
    <form action="{{ route('admin.productdamage.update', $productdamage->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Edit Damage Product
                    <a href="{{ route('admin.order.index') }}" class="btn btn-outline-primary btn-sm float-end">
                        <i class="fa fa-list"></i> Damage Product List
                    </a>
                </h6>
            </div>
            <div class="card-body">
                <div class="mt-2 mb-4 row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">Search Product</label>
                            <div class="input-group">
                                <select class="form-select select2" name="product_id" id="search">
                                     <option value="{{ $productdamage->product_id }}">{{ optional($productdamage->product)->name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="form-label">Qty</label>
                        <div class="input-group">
                            <input type="text" name="qty" class="form-control" min="1" value="{{ $productdamage->qty }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="form-label">@lang('Date')</label>
                            <div class="input-group">
                                <input type="date" name="date" class="form-control"
                                    value="{{ $productdamage->date }}" required>
                            </div>
                        </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="form-group py-3">
                        <label class="form-label">Reason</label>
                        <div class="input-group">
                            <textarea name="reason" class="form-control" required>{{ $productdamage->reason }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.productdamage.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: .375rem !important;
            height: 42px !important;
        }

        .no-focus:focus {
            outline: none;
        }

        .no-border {
            border: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 0px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {

            $('#search').select2({
                ajax: {
                    url: "{{ route('admin.order.searchProduct') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                }
            });

        });


    </script>
@endpush

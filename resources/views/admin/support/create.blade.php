@extends('admin.layouts.app', ['title' => 'Add New Support'])
@section('panel')
    <form action="{{ route('admin.order.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Add New Support <a href="{{ route('admin.support.pending') }}"
                        class="btn btn-outline-primary btn-sm float-end"> <i class="fa fa-list"></i>Pending
                        List</a>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="pb-3 col-12 col-md-4">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select select2" id="searchCustomer">
                            @if(session('customer'))
                                <option value="{{ session('customer')->id }}">{{ session('customer')->name }}</option>
                            @else
                                <option value="">Select Customer</option>
                            @endif
                        </select>
                    </div>
                    <div class="pb-3 col-12 col-md-12">
                        <label class="form-label">Message</label>
                        <textarea name="message" id="message" rows="6" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')
                        </button>
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
            $('#searchCustomer').select2({
                ajax: {
                    url: "{{ route('admin.order.searchCustomer') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                            type: 'public'
                        };
                    },
                    processResults: function(data) {
                        if (data && Array.isArray(data)) {
                            return {
                                results: data
                            };
                        } else {
                            console.error('Invalid data format:', data);
                            return {
                                results: []
                            };
                        }
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

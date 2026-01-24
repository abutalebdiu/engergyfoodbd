@extends('admin.layouts.app', ['title' => 'Commission Setup'])
@section('panel')
    <form action="{{ route('admin.distributioncommission.update', ['id' => $distributor->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 text-capitalize">Commission Setup for <a href="#">{{ $distributor->name }}</a></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        {{-- Global Controls --}}
                        <div class="mb-3 d-flex">
                            <input type="number" id="globalAmount" class="form-control me-2" placeholder="Set Commission">
                            <select id="globalType" class="form-select me-2">
                                <option value="Percentage">@lang('Percentage')</option>
                                <option value="Flat">@lang('Flat')</option>
                            </select>
                            <button type="button" class="btn btn-primary" onclick="applyGlobalChange()">Apply to All</button>
                        </div>
                    </div>
                    <div class="pb-3 col-12 col-md-12">
                        
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Product Name')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $currentDept = null; 
                                    $sl = 1;
                                @endphp
                                @foreach ($products as $product)
                                    {{-- Department Header --}}
                                    @if ($currentDept !== $product->department_id)
                                        @php $currentDept = $product->department_id; @endphp
                                        <tr class="table-primary">
                                            <td colspan="5" class="text-start">
                                                <strong>{{ $product->department->name ?? 'No Department' }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                        
                                    {{-- Product Row --}}
                                    <tr>
                                        <td>{{ en2bn($sl++) }}</td>
                                        <td class="text-start">{{ $product->name }}
                                            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" name="price[]"
                                                   value="{{ en2bn($product->distributorCommission?->price ?? $product->sale_price) }}" required>
                                        </td>
                                        <td>
                                            <input class="form-control commission-input" type="text" name="amount[]"
                                                   value="{{ en2bn($product->distributorCommission?->amount ?? 0) }}" required>
                                        </td>
                                        <td>
                                            <select class="form-select type-select" name="type[]" required>
                                                <option {{ $product->distributorCommission?->type == 'Percentage' ? 'selected' : '' }} value="Percentage">@lang('Percentage')</option>
                                                <option {{ $product->distributorCommission?->type == 'Flat' ? 'selected' : '' }} value="Flat">@lang('Flat')</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="#" class="btn btn-outline-info float-start">Back</a>
                        <button type="submit" class="btn btn-primary float-end">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('style')
    <style>
        .table tr td.size-50 {
            width: 50%;
        }
    </style>
@endpush

@push('script')
<script>
    function applyGlobalChange() {
        let amount = document.getElementById('globalAmount').value;
        let type = document.getElementById('globalType').value;
    
        if (amount) {
            document.querySelectorAll('.commission-input').forEach(el => {
                el.value = amount;
            });
        }
        if (type) {
            document.querySelectorAll('.type-select').forEach(el => {
                el.value = type;
            });
        }
    }
</script>
@endpush

 

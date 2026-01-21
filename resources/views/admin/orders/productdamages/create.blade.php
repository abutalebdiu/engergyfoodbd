@extends('admin.layouts.app', ['title' => 'Add Damage Product'])

@section('panel')
<form action="{{ route('admin.productdamage.store') }}" method="POST">
    @csrf

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Add Damage Product
                <a href="{{ route('admin.productdamage.index') }}"
                   class="btn btn-outline-primary btn-sm float-end">
                    <i class="fa fa-list"></i> Damage List
                </a>
            </h5>
        </div>

        <div class="card-body">

            {{-- Date --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">@lang('Date')</label>
                    <input type="date"
                           name="date"
                           class="form-control"
                           value="{{ old('date', date('Y-m-d')) }}"
                           required>
                </div>
            </div>

            <div class="row">
                @foreach ($productswithgroupes as $departmentId => $products)
                    @php
                        $departmentName = optional($products->first()->department)->name;
                    @endphp

                    <div class="col-12">
                        <h6 class="text-primary fw-bold mb-2">
                            {{ $departmentName ?: 'No Department' }}
                        </h6>
                    </div>

                    @foreach ($products->chunk(ceil($products->count() / 2)) as $chunk)
                        <div class="col-12 col-md-6">
                            <table class="table table-bordered">

                                <thead>
                                    <tr>
                                        <th width="60%">Product</th>
                                        <th width="15%">Qty</th>
                                        <th width="25%">Reason</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                    @foreach ($chunk as $key => $product)
                                        <tr>
                                            <input type="hidden" name="product_id[]" value="{{ $product->id }}">

                                            <td class="text-start">
                                                {{ $product->name }}
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="qty[]"
                                                       class="form-control qty"
                                                       min="0"
                                                       placeholder="0">
                                            </td>

                                            <td>
                                                <input type="text"
                                                       name="reason[]"
                                                       class="form-control"
                                                       placeholder="Damage reason">
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endforeach
            </div>

            {{-- Submit --}}
            <div class="row mt-3">
                <div class="col-12">
                    <a href="{{ route('admin.productstock.index') }}"
                       class="btn btn-outline-info">
                        Back
                    </a>

                    <button type="submit" class="btn btn-primary float-end">
                        <i class="fa fa-check"></i> Submit
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection


@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInputs = document.querySelectorAll('.qty');

        qtyInputs.forEach((input, index) => {
            input.addEventListener('keydown', function (e) {

                if (e.key === 'Enter' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    qtyInputs[index + 1]?.focus();
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    qtyInputs[index - 1]?.focus();
                }
            });
        });
    });
</script>
@endpush

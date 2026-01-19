@extends('admin.layouts.app', ['title' => 'Order Detail'])
@push('style')
    <style>
        table,
        td,
        th {
            padding: 2px !important;
        }
    </style>
@endpush
@section('panel')
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 text-capitalize">{{ $type }} Receive Product From Supplier </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Order Info</h5>
                    <p>
                        Order No : {{ $order->oid }}, <br>
                        Date : {{ $order->date }}, <br>
                        Media : {{ $order->media }}, <br>
                        Total Amount : {{ $order->totalamount }}
                    </p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="border-bottom">Buyer Info</h5>
                    <p>
                        Buyer Name : {{ optional($order->buyer)->name }}, <br>
                        Buyer Mobile : {{ optional($order->buyer)->mobile }}, <br>
                        Buyer Email : {{ optional($order->buyer)->email }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Product Detail</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.order.supplier.product.received') }}" method="post">
                @csrf
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Supplier</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>label</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>QTY</th>
                            <th>Received</th>
                            <th>Pending QTY</th>
                            <th>Receive QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderdetail as $odetail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($odetail->supplier)->name }}</td>
                                <td>{{ $odetail->code }}</td>
                                <td>{{ $odetail->name }}</td>
                                <td>
                                    @if ($odetail->image)
                                        <img src="{{ asset($odetail->image) }}" alt="" style="width: 100px">
                                    @endif
                                </td>
                                <td>{{ $odetail->label }}</td>
                                <td>{{ $odetail->color }}</td>
                                <td>{{ $odetail->size }}</td>
                                <td>{{ $odetail->qty }}</td>
                                <td>{{ $odetail->receive_qty }}</td>
                                <td>{{ $odetail->pending_qty }}</td>
                                <td>
                                    <input type="hidden" name="order_detail_id[]" value="{{ $odetail->id }}">
                                    <input type="text" name="receive_qty[]" value="0" class="form-control" size="1">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="11">  </td>
                            <td>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Submit</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>



    <x-destroy-confirmation-modal />
    @push('script')
    @endpush
@endsection

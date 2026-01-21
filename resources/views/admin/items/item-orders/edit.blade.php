@extends('admin.layouts.app', ['title' => 'Edit Item Order'])

@section('panel')
<form action="{{ route('admin.items.itemOrder.update', $itemorder->id) }}" method="POST">
@csrf

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            Edit Item Order
            <a href="{{ route('admin.items.itemOrder.index') }}"
               class="btn btn-outline-primary btn-sm float-end">
                <i class="fa fa-list"></i> Order List
            </a>
        </h6>
    </div>

    <div class="card-body">

        <div class="row border-bottom mb-4">
            <div class="col-md-4 pb-3">
                <label>Supplier</label>
                <select name="supplier_id" class="form-select select2">

                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplier->id == $itemorder->supplier_id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 pb-3">
                <label>Reference Invoice</label>
                <input type="text" name="reference_invoice_no"
                       class="form-control"
                       value="{{ $itemorder->reference_invoice_no }}">
            </div>

            <div class="col-md-4 pb-3">
                <label>Purchase Date</label>
                <input type="date" name="date"
                       class="form-control"
                       value="{{ $itemorder->date }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <select id="itemSelect" class="form-select select2">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-price="{{ $item->price }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-success w-100" id="addItem">
                    + Add Item
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th width="300">Item</th>
                            <th width="120">Unit</th>
                            <th width="120">Qty</th>
                            <th width="120">Price</th>
                            <th width="120">Total</th>
                            <th width="30">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyappend"></tbody>
                </table>
            </div>

            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <th>Sub Total</th>
                        <td>
                            <input type="text" readonly
                                   name="sub_total"
                                   class="form-control sub-total"
                                   value="{{ $itemorder->subtotal }}">
                        </td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <td>
                            <input type="text"
                                   name="discount"
                                   class="form-control discount"
                                   value="{{ $itemorder->discount }}">
                        </td>
                    </tr>
                    <tr>
                        <th>Transport Cost</th>
                        <td>
                            <input type="text"
                                   name="transport_cost"
                                   class="form-control transport_cost"
                                   value="{{ $itemorder->transport_cost }}">
                        </td>
                    </tr>
                    <tr>
                        <th>Labour Cost</th>
                        <td>
                            <input type="text"
                                   name="labour_cost"
                                   class="form-control labour_cost"
                                   value="{{ $itemorder->labour_cost }}">
                        </td>
                    </tr>
                    <tr>
                        <th>Grand Total</th>
                        <td>
                            <input type="text" readonly
                                   name="grand_total"
                                   class="form-control grand-total"
                                   value="{{ $itemorder->totalamount }}">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <a href="{{ route('admin.items.itemOrder.index') }}"
           class="btn btn-outline-info">Back</a>

        <button type="submit" class="btn btn-primary float-end">
            Update Order
        </button>
    </div>
</div>
</form>
@endsection

@include('components.select2')

@push('script')
<script>
    let cart = {};

    function n(v){
        v = parseFloat(v);
        return isNaN(v) ? 0 : v;
    }

    @foreach($itemorder->itemOrderDetail as $d)
    cart[{{ $d->item_id }}] = {
        name  : "{{ $d->product->name }}",
        qty   : {{ $d->purchase_qty }},
        price : {{ $d->price }},
        unit  : {{ $d->purchase_unit_id }}
    };
    @endforeach

    function recalculateAll(){
        let sub = 0;

        $('.tbodyappend tr').each(function(){
            let qty   = n($(this).find('.qty').val());
            let price = n($(this).find('.price').val());
            let unit  = $(this).find('.unit').val();
            let total = qty * price;

            $(this).find('.amount').val(total.toFixed(2));
            sub += total;
        });

        $('.sub-total').val(sub.toFixed(2));

        let discount  = n($('.discount').val());
        let transport = n($('.transport_cost').val());
        let labour    = n($('.labour_cost').val());

        $('.grand-total').val((sub - discount + transport + labour).toFixed(2));
    }

    let units = {!! json_encode($units) !!};

    function renderTable(){
        let html = '';
        let i = 1;

        $.each(cart, function(id, item){
            html += `
            <tr data-id="${id}">
                <td>${i++}</td>
                <td>
                    ${item.name}
                    <input type="hidden" name="items[${id}][id]" value="${id}">
                </td>

                <td>
                    <select name="items[${id}][unit]" class="form-select unit">
                        <option value="">Select Unit</option>
                        ${units.map(unit => `<option value="${unit.id}"  ${unit.id == item.unit ? 'selected' : ''}>${unit.name}</option>`).join('')}
                    </select>
                </td>

                <td>
                    <input type="number" min="1"
                        class="form-control qty"
                        name="items[${id}][qty]"
                        value="${item.qty}">
                </td>
                <td>
                    <input type="number" step="0.01"
                        class="form-control price"
                        name="items[${id}][price]"
                        value="${item.price}">
                </td>
                <td>
                    <input type="text" readonly
                        class="form-control amount"
                        name="items[${id}][total]"
                        value="0.00">
                </td>
                <td>
                    <button type="button"
                            class="btn btn-danger btn-sm remove">✕</button>
                </td>
            </tr>`;
        });

        $('.tbodyappend').html(html);
        recalculateAll();
    }

    $('#addItem').on('click', function(){
        let opt = $('#itemSelect option:selected');
        let id  = opt.val();

        if(!id){
            alert('Select item first');
            return;
        }

        if(cart[id]){
            cart[id].qty += 1;
        }else{
            cart[id] = {
                name  : opt.data('name'),
                qty   : 1,
                price : n(opt.data('price'))
            };
        }

        $('#itemSelect').val('').trigger('change');
        renderTable();
    });

    $(document).on('keyup change','.qty,.price',recalculateAll);
    $(document).on('keyup change','.discount,.transport_cost,.labour_cost',recalculateAll);

    $(document).on('click','.remove',function(){
        let id = $(this).closest('tr').data('id');
        delete cart[id];
        renderTable();
    });

    $(document).ready(function(){
        renderTable();
    });
</script>
@endpush

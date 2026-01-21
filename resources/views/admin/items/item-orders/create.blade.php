@extends('admin.layouts.app', ['title' => __('New Order')])

@section('panel')
<form action="{{ route('admin.items.itemOrder.store') }}" method="POST">
@csrf

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            @lang('New Order')
            <a href="{{ route('admin.items.itemOrder.index') }}"
               class="btn btn-outline-primary btn-sm float-end">
                <i class="fa fa-list"></i> @lang('Order List')
            </a>
        </h6>
    </div>

    <div class="card-body">

        <div class="row border-bottom mb-4">
            <div class="col-md-4 pb-3">
                <label class="form-label">@lang('Suppliers')</label>
                <select name="supplier_id" class="form-select select2">
                    <option value="">@lang('Select Supplier')</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 pb-3">
                <label class="form-label">@lang('Reference Invoice No')</label>
                <input type="text" name="reference_invoice_no" class="form-control">
            </div>

            <div class="col-md-4 pb-3">
                <label class="form-label">@lang('Purchase Date')</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>@lang('Item Category')</label>
                <select id="item_category_id" class="form-select select2">
                    <option value="">@lang('Select Category')</option>
                    @foreach ($itemcategories as $cat)
                        <option data-items='@json($cat->items)'>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8">
                <label>@lang('Items')</label>
                <select id="search" class="form-select select2">
                    <option value="">@lang('Select Item')</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th width="200">@lang('Item')</th>
                            <th width="90">@lang('Unit')</th>
                            <th width="90">@lang('Qty')</th>
                            <th width="120">@lang('Price')</th>
                            <th width="140">@lang('Total')</th>
                            <th width="70">@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyappend"></tbody>
                </table>
            </div>

            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <th>@lang('Sub Total')</th>
                        <td>
                            <input type="text" readonly
                                   name="sub_total"
                                   class="form-control sub-total"
                                   value="0.00">
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('Discount')</th>
                        <td>
                            <input type="text" name="discount"
                                   class="form-control discount"
                                   value="0">
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('Transport Cost')</th>
                        <td>
                            <input type="text" name="transport_cost"
                                   class="form-control transport_cost"
                                   value="0">
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('Labour Cost')</th>
                        <td>
                            <input type="text" name="labour_cost"
                                   class="form-control labour_cost"
                                   value="0">
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('Grand Total')</th>
                        <td>
                            <input type="text" readonly
                                   name="grand_total"
                                   class="form-control grand-total"
                                   value="0.00">
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
           class="btn btn-outline-info">@lang('Back')</a>

        <button type="submit" class="btn btn-primary float-end">
            @lang('Submit')
        </button>
    </div>
</div>

</form>
@endsection

@include('components.select2')

@push('script')
<script>
    let cart = {};

    function num(v){
        v = parseFloat(v);
        return isNaN(v) ? 0 : v;
    }

    function recalculateAll(){
        let subTotal = 0;

        $('.tbodyappend tr').each(function(){
            let qty   = num($(this).find('.qty').val());
            let price = num($(this).find('.price').val());

            let total = qty * price;
            $(this).find('.amount').val(total.toFixed(2));
            subTotal += total;
        });

        $('.sub-total').val(subTotal.toFixed(2));

        let discount  = num($('.discount').val());
        let transport = num($('.transport_cost').val());
        let labour    = num($('.labour_cost').val());

        let grand = subTotal - discount + transport + labour;
        $('.grand-total').val(grand.toFixed(2));
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
                    <select name="items[${id}][unit]" class="form-select">
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

    $('#item_category_id').on('change', function(){
        let items = $(this).find(':selected').data('items') || [];
        let opt = `<option value="">Select Item</option>`;
        items.forEach(item=>{
            opt += `<option value="${item.id}"
                        data-price="${item.price}"
                        data-unit="${item.unit_id}">
                        ${item.name}
                    </option>`;
        });
        $('#search').html(opt).trigger('change');
    });

    $('#search').on('change', function(){
        let op = $(this).find(':selected');
        let id = op.val();
        if(!id) return;

        if(cart[id]){
            cart[id].qty++;
        }else{
            cart[id] = {
                name: op.text(),
                qty: 1,
                price: num(op.data('price')),
                unit: op.data('unit')
            };
        }
        renderTable();
        $(this).val('');
    });

    $(document).on('keyup change', '.qty, .price', recalculateAll);
    $(document).on('keyup change', '.discount, .transport_cost, .labour_cost', recalculateAll);

    $(document).on('click', '.remove', function(){
        let id = $(this).closest('tr').data('id');
        delete cart[id];
        renderTable();
    });
</script>
@endpush

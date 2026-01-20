<table class="table table-bordered table-hover table-striped" id="productsTable">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th>@lang('SL')</th>
            <th>@lang('Name')</th>
            <th>@lang('Weight')</th>
            <th>@lang('Department')</th>
            <th>@lang('Sale/Dealar Price')</th>
            <th>@lang('Store/Shop Price')</th>
            <th>@lang('Retail Price')</th>
            <th>@lang('Stock')</th>
            <th>@lang('Recipe')</th>
            <th>@lang('PP Item')</th>
            <th>@lang('PP Weight')</th>
            <th>@lang('Box Item')</th>
            <th>@lang('Striker Item')</th>
            <th>@lang('Status')</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = ($productss->currentPage() - 1) * $productss->perPage() + 1;
        @endphp
        @foreach ($productswithgroupes as $departmentId => $products)
            @php
                $departmentName = optional($products->first()->department)->name;
            @endphp
            <tr>
                <td colspan="15" class="font-weight-bold text-primary text-start">
                    {{ $departmentName ?: 'No Department' }}
                </td>
            </tr>
            @forelse($products as $item)
                <tr>
                    <td>
                        <div class="btn-group">
                            <button data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('admin.product.edit', $item->id) }}"
                                        class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> @lang('Edit')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.product.show', $item->id) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-eye"></i> @lang('Show')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.product.sales', $item->id) }}"
                                        class="btn btn-info">
                                        <i class="fa fa-shopping-cart"></i> @lang('Sales')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.product.production', $item->id) }}"
                                        class="btn btn-info">
                                        <i class="fa fa-shopping-cart"></i> @lang('Productions')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.product.customer.price', $item->id) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-eye"></i> @lang('Customer Price')
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="{{ $item->id }}"
                                        data-question="@lang('Are you sure you want to delete this item?')"
                                        data-action="{{ route('admin.product.destroy', $item->id) }}"
                                        class="btn btn-danger confirmationBtn">
                                        <i class="bi bi-trash"></i> @lang('Delete')
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </td>
                    <td> {{ en2bn($i++) }} </td>
                    <td style="text-align: left"><a href="{{ route('admin.product.show', $item->id) }}">
                            {{ $item->name }} </a> </td>
                    <td> {{ $item->weight }} </td>
                    <td> {{ optional($item->department)->name }} </td>
                    <td> {{ en2bn($item->sale_price) }}</td>
                    <td> {{ en2bn($item->shop_price) }}</td>
                    <td> {{ en2bn($item->retail_price) }}</td>
                    <td> {{ en2bn(number_format($item->getstock($item->id), 0, '.', ',')) }}</td>
                    <td> {{ $item->productrecipe->count() != 0 ? 'Yes' : '' }} </td>
                    <td class="text-wrap" style="text-align:left"> {{ optional($item->ppitem)->name }} </td>
                    <td> {{ en2bn($item->pp_weight) }}</td>
                    <td> {{ optional($item->boxitem)->name }} </td>
                    <td> {{ optional($item->strikeritem)->name }} </td>
                    <td>
                        @if ($item->status == 'Active')
                            <a href="{{ route('admin.product.status', $item->id) }}"
                                class="btn btn-success btn-sm">@lang('Active')</a>
                        @else
                            <a href="{{ route('admin.product.status', $item->id) }}"
                                class="btn btn-danger btn-sm">@lang('Inactive')</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                </tr>
            @endforelse
        @endforeach
    </tbody>
</table>


@if ($productss->hasPages())
    <div class="card-footer">
        {{ $productss->links() }}
    </div>
@endif
<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th style="width:5%">@lang('SL')</th>
            <th>@lang('Name')</th>
            <th>@lang('Category')</th>
            <th>@lang('Unit')</th>
            <th>@lang('Weight') (গ্রাম)</th>
            <th>@lang('Price')</th>
            <th>@lang('Stock')</th>
            <th>@lang('Value')</th>
        </tr>
    </thead>
    <tbody id="ItemTable">
        @php
            $i = 1;
            $totalqty = 0;
            $totalvalue = 0;
        @endphp
        @forelse($itemsgroupes as $categoryId => $items)
            @php
                $categoryName = optional($items->first()->category)->name;
            @endphp

            <tr>
                <td colspan="9" class="font-weight-bold text-primary text-start">
                    {{ $categoryName ?: 'No Category' }}
                </td>
            </tr>

                @php
                    $totalgroupqty   = 0;
                    $totalgroupvalue = 0;
                @endphp

            @forelse($items as $item)
                @php
                    $totalqty   += $item->stock($item->id);
                    $totalvalue += $item->stock($item->id) * $item->price;

                    $totalgroupqty   += $item->stock($item->id);
                    $totalgroupvalue += $item->stock($item->id) * $item->price;
                @endphp
                <tr>
                    <td>
                        <div class="btn-group">
                            <button data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('admin.items.item.create', $item->id) }}"
                                        class="dropdown-item"><i class="bi bi-pencil-fill"></i>Edit</a>
                                </li>
                                <li>
                                    <button class="btn btn-sm confirmationBtn btn-outline--success"
                                        data-action="{{ route('admin.items.item.destroy', $item->id) }}"
                                        data-question="@lang('Are you sure to Delete this Product?')" type="button">
                                        <i class="fa fa-trash"></i>@lang('Delete')
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td> {{ en2bn($i++) }} </td>
                    <td class="text-start"> {{ $item->name }} </td>
                    <td> {{ optional($item->category)->name }} </td>
                    <td> {{ $item->unit?->name ?? 'N/A' }}</td>
                    <td> {{ en2bn($item->weight_gram) }}</td>
                    <td> {{ en2bn($item->price ?? '0.00') }}</td>
                    <td> {{ en2bn(number_format($item->stock($item->id) ?? '0.00'),2,'.',',') }}</td>
                    <td> {{ en2bn(number_format($item->stock($item->id) ?? '0.00' * $item->price, 2, '.', ',')) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-muted" colspan="9">No Data Found</td>
                </tr>
            @endforelse
                <tr>
                <th colspan="6">@lang('Total')</th>
                <th>{{ en2bn(number_format($totalgroupqty, 2, '.', ',')) }}</th>
                <th>{{ en2bn(number_format($totalgroupvalue, 2, '.', ',')) }}</th>
                </tr>

        @empty
            <tr>
                <td class="text-center text-muted" colspan="9">No Data Found</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">@lang('Total')</th>
            <th>{{ en2bn(number_format($totalqty, 2, '.', ',')) }}</th>
            <th>{{ en2bn(number_format($totalvalue, 2, '.', ',')) }}</th>
        </tr>
    </tfoot>
</table><!-- table end -->

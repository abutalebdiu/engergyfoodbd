<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>@lang('Action')</th>
            <th>@lang('ID')</th>
            <th>@lang('Name')</th>
            <th>@lang('Mobile')</th>
            <th>@lang('Address')</th>
            <th>@lang('Commission Type')</th>
            <th>@lang('Commission')</th>
            <th>@lang('Marketer Name')</th>
            <th>@lang('Distributor')</th>
            <th>@lang('Opening Due')</th>
            <th>@lang('Total Due')</th>
            <th>@lang('Total Order')</th>
            <th>@lang('Status')</th>
        </tr>
    </thead>
    <tbody id="customerlists">
        @php
            $totaldue = 0;
            $previousdue = 0;
            $totalorder = 0;
        @endphp
        @forelse($users as $user)
            @php
                $totaldue += $user->receivable($user->id);
                $previousdue += $user->current_due ?? 0;
                $totalorder += $user->orders->count();
            @endphp
            <tr>
                <td>
                    <div class="btn-group">
                        <button data-bs-toggle="dropdown">
                            <span class="btn btn-primary btn-sm">Action</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('admin.customers.detail', $user->id) }}">
                                    <i class="las la-desktop"></i> @lang('Details')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.customers.statement', $user->id) }}">
                                    <i class="fa fa-money-bill"></i> @lang('Statement')
                                </a>
                            </li>
                            @if (Auth::guard('admin')->user()->hasPermission('admin.commissioninvoice.create'))
                            <li>
                                <a href="{{ route('admin.referenceCommision', $user->id) }}">
                                    <i class="las la-desktop"></i> @lang('Setup Ref. Commission')
                                </a>
                            </li>
                            @endif
                            
                            <li>
                                <a href="{{ route('admin.referenceCommisionpdf', $user->id) }}">
                                    <i class="las la-download"></i> @lang('Download Ref. Commission')
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('admin.customers.productcomissionlist', $user->id) }}"
                                    target="_blank"><i class="las la-print"></i> Commission Print</a>
                            </li>
                            @if (Auth::guard('admin')->user()->hasPermission('admin.customer.store'))
                            <li>
                                <a href="{{ route('admin.customers.delete', $user->id) }}"
                                    onclick="return confirm('Are you sure! Delete this Customer.')">
                                    <i class="las la-desktop"></i> @lang('Delete')
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </td>
                <td>{{ en2bn($user->uid) }}</td>
                <td class="text-start"> <a
                        href="{{ route('admin.customers.statement', $user->id) }}">{{ $user->name }}</a>
                </td>

                <td>{{ $user->mobile }}</td>
                <td class="text-start">{{ $user->address }}</td>
                <td>{{ __($user->commission_type) }}</td>
                <td>{{ en2bn($user->commission) }}
                <td>{{ optional($user->reference)->name }} @if ($user->reference)
                        - ({{ en2bn(optional($user->reference)->amount) }}%)
                    @endif
                </td>
                <td>{{ optional($user->distribution)->name }}</td>
                <td style="text-align:right;padding-right:10px">
                    {{ en2bn(number_format($user->last_month_due ?? 0, 2)) }}</td>
                <td style="text-align:right;padding-right:10px">
                    {{ en2bn(number_format($user->receivable($user->id), 2)) }}</td>
                <td>{{ en2bn($user->orders->count()) }}</td>
                <td>
                    @if ($user->status == 1)
                        <a href="{{ route('admin.customers.status', $user->id) }}"
                            class="btn btn-success btn-sm">@lang('Active')</a>
                    @else
                        <a href="{{ route('admin.customers.status', $user->id) }}"
                            class="btn btn-danger btn-sm">@lang('Inactive')</a>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="12" class="text-center">@lang('No data found')</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9">@lang('Total')</th>
            <th style="text-align:right;padding-right:10px">
                {{ en2bn(number_format($previousdue, 2, '.', '')) }}</th>
            <th style="text-align:right;padding-right:10px">
                {{ en2bn(number_format($totaldue, 2, '.', '')) }}</th>
            <th style="text-align:right;padding-right:10px">{{ en2bn(number_format($totalorder)) }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>


<div id="pagination-links">
    {{ $users->links() }}
</div>

@if (Auth::guard('admin')->user()->hasPermission('admin.reports'))
    <li class="sidebar--menu sidebar--dropdown {{ menuActive(['admin.reports*', 'admin.reports*', 'admin.reports']) }}">
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bi bi-bar-chart"></i>
            </div>
            <div class="menu-title">@lang('Reports')</div>
        </a>
        <ul>

            <li class="{{ menuActive('admin.reports.trialbalance') }}">
                <a href="{{ route('admin.reports.trialbalance') }}"><i class="fa fa-list"></i>@lang('Trial Balance')</a>
            </li>
            
            <li class="{{ menuActive('admin.reports.cashregister') }}">
                <a href="{{ route('admin.reports.cashregister') }}"><i class="fa fa-list"></i>@lang('Cash Register')</a>
            </li>

            <li class="{{ menuActive('admin.reports.balancesheets') }}">
                <a href="{{ route('admin.reports.balancesheets') }}"><i class="fa fa-list"></i>@lang('Balance Sheets')</a>
            </li>


            <li class="{{ menuActive('admin.reports.summeryreport') }}">
                <a href="{{ route('admin.reports.summeryreport') }}"><i class="fa fa-list"></i>@lang('Summery Report')</a>
            </li>

            <li class="{{ menuActive('admin.reports.summery') }}">
                <a href="{{ route('admin.reports.summery') }}"><i class="fa fa-list"></i>@lang('Summery')</a>
            </li>
            
            <li class="{{ menuActive('admin.reports.monthlycustomersummary') }}">
                <a href="{{ route('admin.reports.monthlycustomersummary') }}"><i class="fa fa-list"></i>@lang('Monthly Customer Summary')</a>
            </li>

            @if (Auth::guard('admin')->user()->hasPermission('admin.asset.list'))
                <li class="{{ menuActive('admin.asset.index') }}">
                    <a href="{{ route('admin.asset.index') }}"><i class="fa fa-list"></i>@lang('Asset')</a>
                </li>
            @endif
             
            @if (Auth::guard('admin')->user()->hasPermission('admin.asset.list'))
                <li class="{{ menuActive('admin.liabilitie.index') }}">
                    <a href="{{ route('admin.liabilitie.index') }}"><i class="fa fa-list"></i>@lang('Liabilitie')</a>
                </li>
            @endif


            <li class="{{ menuActive('admin.reports.dailyreports') }}">
                <a href="{{ route('admin.reports.dailyreports') }}"><i class="fa fa-list"></i>@lang('Daily Reports')</a>
            </li>

            <li class="{{ menuActive('admin.reports.dailyarchive') }}">
                <a href="{{ route('admin.reports.dailyarchive') }}"><i class="fa fa-list"></i>@lang('Daily Archive')</a>
            </li>
            
            
            <li class="{{ menuActive('admin.reports.customerdailyreports') }}">
                <a href="{{ route('admin.reports.customerdailyreports') }}"><i class="fa fa-list"></i>@lang('Customers Daily Sales Reports')</a>
            </li>


            @if (Auth::guard('admin')->user()->hasPermission('admin.purchasesell.list'))
                <li class="{{ menuActive('admin.reports.purchasesell') }}">
                    <a href="{{ route('admin.reports.purchasesell') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Purchase Sell')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.customersupplier.list'))
                <li class="{{ menuActive('admin.reports.customersupplier') }}">
                    <a href="{{ route('admin.reports.customersupplier') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Customer / Supplier Report')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.stockreport.list'))
                <li class="{{ menuActive('admin.reports.stockreport') }}">
                    <a href="{{ route('admin.reports.stockreport') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Stock Report')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.itemstockreport.list'))
                <li class="{{ menuActive('admin.reports.itemstockreport') }}">
                    <a href="{{ route('admin.reports.itemstockreport') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Item Stock Report')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.stockadjustment.list'))
                <li class="{{ menuActive('admin.reports.stockadjustment') }}">
                    <a href="{{ route('admin.reports.stockadjustment') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Stock Adjustment Report')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.productpurchasereport.list'))
                <li class="{{ menuActive('admin.reports.productpurchasereport') }}">
                    <a href="{{ route('admin.reports.productpurchasereport') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Product Purchase Report')</a>
                </li>
            @endif

            @if (Auth::guard('admin')->user()->hasPermission('admin.productsalesreport.list'))
                <li class="{{ menuActive('admin.reports.productsalesreport') }}">
                    <a href="{{ route('admin.reports.productsalesreport') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Product Sales Report')</a>
                </li>
            @endif
            {{--
        <li class="{{ menuActive('admin.reports.expensereport') }}">
            <a href="{{ route('admin.reports.expensereport') . "?filter=this_month" }}"><i
                    class="bi bi-plus-square"></i>@lang('Expense Report')</a>
        </li> --}}

            @if (Auth::guard('admin')->user()->hasPermission('admin.registerreport.list'))
                <li class="{{ menuActive('admin.reports.registerreport') }}">
                    <a href="{{ route('admin.reports.registerreport') . '?filter=this_month' }}"><i
                            class="bi bi-plus-square"></i>@lang('Register Report')</a>
                </li>
            @endif
            
           <li class="{{ menuActive('admin.reports.daily-reports') }}">
                <a href="{{ route('admin.reports.daily-reports') }}"><i
                        class="bi bi-plus-square"></i>@lang('Daily Reports') <span class="badge bg-danger">New</span></a>
            </li>
             <li class="{{ menuActive('admin.reports.daily-item-reports') }}">
                <a href="{{ route('admin.reports.daily-item-reports') }}"><i
                        class="bi bi-plus-square"></i>@lang('Daily Item Reports') <span class="badge bg-danger me-2">New</span></a>
            </li>
        </ul>
    </li>
@endif

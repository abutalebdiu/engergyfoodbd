<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <div>
                {{-- <img src="{{ siteLogo() }}" alt="" style="width:70%"> --}}
                <h4>@lang('Admin Panel')</h4>
            </div>
        </div>
        <div class="toggle-icon ms-auto">

            <i class="bi bi-list"></i>
        </div>
    </div>
    <ul class="metismenu sidebar__menu-main" id="menu">

        @if (Auth::guard('admin')->user()->hasPermission('admin.dashboard'))
            <li class="sidebar--menu {{ menuActive('admin.dashboard') }}">
                <a href="{{ route('admin.dashboard') }}">
                    <div class="parent-icon"><i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="menu-title">@lang('Dashboard')</div>
                </a>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.setting.access.product'))
            <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.product*') }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-shop"></i>
                    </div>
                    <div class="menu-title">@lang('Products')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.product.create'))
                        <li class="{{ menuActive('admin.product.create') }}">
                            <a href="{{ route('admin.product.create') }}">
                                <i class="bi bi-record-circle"></i>@lang('Add New Product')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.product.list'))
                        <li class="{{ menuActive('admin.product.index') }}">
                            <a href="{{ route('admin.product.index') }}">
                                <i class="bi bi-record-circle"></i>@lang('Products List')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.product.list'))
                        <li class="{{ menuActive('admin.productstock.index') }}">
                            <a href="{{ route('admin.productstock.index') }}">
                                <i class="bi bi-record-circle"></i>@lang('Product Settlement List')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.product.list'))
                        <li class="{{ menuActive('admin.productdamage.index') }}">
                            <a href="{{ route('admin.productdamage.index') }}">
                                <i class="bi bi-record-circle"></i>@lang('Product Damage List')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.customerproductdamage.list'))
                        <li class="{{ menuActive('admin.customerproductdamage.index') }}">
                            <a href="{{ route('admin.customerproductdamage.index') }}">
                                <i class="bi bi-record-circle"></i>@lang('Customers Products Damage List')
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.access'))
            <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.quotation*') }}">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bi bi-cart"></i>
                    </div>
                    <div class="menu-title">@lang('Sales Quotations')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.create'))
                        <li class="{{ menuActive('admin.quotation.creata') }}">
                            <a href="{{ route('admin.quotation.create') }}"><i
                                    class="bi bi-circle"></i>@lang('New Quotation')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.list'))
                        <li class="{{ menuActive('admin.quotation.index') }}">
                            <a href="{{ route('admin.quotation.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Quotation List')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.deleted.list'))
                        <li class="{{ menuActive('admin.quotation.deleted.list') }}">
                            <a href="{{ route('admin.quotation.deleted.list') }}"><i
                                    class="bi bi-circle"></i>@lang('Quotation Deleted List')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.quotation.productdemand'))
                        <li class="{{ menuActive('admin.quotation.product.demand') }}">
                            <a href="{{ route('admin.quotation.product.demand') }}"><i
                                    class="bi bi-circle"></i>@lang('Product Demand List')</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.order.access') ||
                Auth::guard('admin')->user()->hasPermission('admin.pointofsale.list'))
            <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.order*') }}">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bi bi-cart"></i>
                    </div>
                    <div class="menu-title">@lang('Sale Orders')</div>
                </a>
                <ul>
                    {{-- <li class="{{ menuActive('admin.order.create') }}">
                    <a class="" href="{{ route('admin.order.create') }}"><i class="bi bi-circle"></i>Add
                        New Order
                    </a>
                </li> --}}
                    @if (Auth::guard('admin')->user()->hasPermission('admin.order.create') ||
                            Auth::guard('admin')->user()->hasPermission('admin.pointofsale.create'))
                        <li class="{{ menuActive('admin.order.pos.create') }}">
                            <a class="" href="{{ route('admin.order.pos.create') }}"><i class="bi bi-circle"></i>
                                @lang('New Order')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.order.list') ||
                            Auth::guard('admin')->user()->hasPermission('admin.pointofsale.list'))
                        <li class="{{ menuActive('admin.order.index') }}">
                            <a class="" href="{{ route('admin.order.index') }}"><i class="bi bi-circle"></i>
                                @lang('Order List')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.orderpayment.list'))
                        <li class="{{ menuActive('admin.orderpayment.index') }}">
                            <a class="" href="{{ route('admin.orderpayment.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Order Payment')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.orderreturn.list'))
                        <li class="{{ menuActive('admin.orderreturn.index') }}">
                            <a class="" href="{{ route('admin.orderreturn.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Order Return')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.commissioninvoice.list'))
                        <li>
                            <a href="{{ route('admin.commissioninvoice.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Customer Commission Invoices')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.commissioninvoice.list'))
                        <li>
                            <a href="{{ route('admin.marketercommission.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Marketer Commissions')</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.marketercommissionpayment.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Marketer Commission Payments')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.order.productorder'))
                        <li class="{{ menuActive('admin.order.product.demand') }}">
                            <a href="{{ route('admin.order.product.demand') }}"><i
                                    class="bi bi-circle"></i>@lang('Product Order List')</a>
                        </li>
                    @endif
                    <li class="{{ menuActive('admin.order.date.customerorder') }}">
                        <a href="{{ route('admin.order.date.customerorder') }}"><i
                                class="bi bi-circle"></i>@lang('Date Wise Customer Order')</a>
                    </li>
                    <li class="{{ menuActive('admin.orderdetail.index') }}">
                        <a href="{{ route('admin.orderdetail.index') }}"><i class="bi bi-circle"></i>@lang('Order Detail')</a>
                    </li>
                </ul>
            </li>
        @endif

        {{-- <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.quotation*') }}">
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-cart"></i>
                </div>
                <div class="menu-title">Sales Quotations</div>
            </a>
            <ul>
                <li class="{{ menuActive('admin.quotation.creata') }}">
                    <a href="{{ route('admin.quotation.create') }}"><i class="bi bi-circle"></i>@lang('Add New Quotation')</a>
                </li>
                <li class="{{ menuActive('admin.quotation.index') }}">
                    <a href="{{ route('admin.quotation.index') }}"><i class="bi bi-circle"></i>@lang('Quotation List')</a>
                </li>
            </ul>
        </li> --}}

        {{-- <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.purchase*') }}">
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bi bi-cart"></i>
                </div>
                <div class="menu-title">Purchase</div>
            </a>
            <ul>
                <li class="{{ menuActive('admin.purchase.create') }}">
                    <a class="" href="{{ route('admin.purchase.create') }}"><i class="bi bi-circle"></i>Add
                        New Purchase
                    </a>
                </li>
                <li class="{{ menuActive('admin.purchase.index') }}">
                    <a class="" href="{{ route('admin.purchase.index') }}"><i class="bi bi-circle"></i>Purchase
                        List</a>
                </li>
                <li class="{{ menuActive('admin.ordersupplierpayment.index') }}">
                    <a class="" href="{{ route('admin.ordersupplierpayment.index') }}"><i
                            class="bi bi-circle"></i>Supplier Payment
                        List</a>
                </li>
                <li class="{{ menuActive('admin.purchasereturn.index') }}">
                    <a class="" href="{{ route('admin.purchasereturn.index') }}"><i
                            class="bi bi-circle"></i>Purchase Return
                    </a>
                </li>
                <li class="{{ menuActive('admin.purchasereturnpayment.index') }}">
                    <a class="" href="{{ route('admin.purchasereturnpayment.index') }}"><i
                            class="bi bi-circle"></i>Purchase Return Payment
                    </a>
                </li>
            </ul>
        </li> --}}

        @if (Auth::guard('admin')->user()->hasPermission('admin.item.access'))
            <li class="sidebar--menu sidebar--dropdown">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bi bi-diagram-2"></i>
                    </div>
                    <div class="menu-title">@lang('Items')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.item.create'))
                        <li class="{{ menuActive('admin.items.item.create') }}">
                            <a class="" href="{{ route('admin.items.item.create') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Add New Item')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.item.list'))
                        <li class="{{ menuActive('admin.items.item.index') }}">
                            <a class="" href="{{ route('admin.items.item.index') }}"><i class="bi bi-circle"></i>
                                @lang('Item List')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemcategory.list'))
                        <li class="{{ menuActive('admin.items.itemCategory.index') }}">
                            <a class="" href="{{ route('admin.items.itemCategory.index') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Item Category')
                            </a>
                        </li>
                    @endif
                    <li class="{{ menuActive('admin.itemtstock.index') }}">
                        <a class="" href="{{ route('admin.itemtstock.index') }}"><i class="bi bi-circle"></i>
                            @lang('Item Stock Settlement')
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        @if (Auth::guard('admin')->user()->hasPermission('admin.itemorder.access'))
            <li class="sidebar--menu sidebar--dropdown">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bi bi-diagram-2"></i>
                    </div>
                    <div class="menu-title">@lang('Item Purchase')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemorder.create'))
                        <li class="{{ menuActive('admin.items.itemOrder.create') }}">
                            <a class="" href="{{ route('admin.items.itemOrder.create') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('New Order')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemorder.list'))
                        <li class="{{ menuActive('admin.items.itemOrder.index') }}">
                            <a class="" href="{{ route('admin.items.itemOrder.index') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Order List')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemorderpayment.list'))
                        <li class="{{ menuActive('admin.itemorderpayment.index') }}">
                            <a class="" href="{{ route('admin.itemorderpayment.index') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Item Order Payment')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemreturn.list'))
                        <li class="{{ menuActive('admin.itemreturn.index') }}">
                            <a class="" href="{{ route('admin.itemreturn.index') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Order Return')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.itemreturnpayment.list'))
                        <li class="{{ menuActive('admin.orderreturnpayment.index') }}">
                            <a class="" href="{{ route('admin.itemreturnpayment.index') }}"><i
                                    class="bi bi-circle"></i>
                                @lang('Order Return Payment')</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif





        {{-- <li
            class="sidebar--menu sidebar--dropdown {{ menuActive('admin.serviceinvoice*', 'admin.serviceinvoicepayment*') }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-currency-dollar"></i>
                </div>
                <div class="menu-title">@lang('Services')</div>
            </a>
            <ul>
                <li class="{{ menuActive('admin.suppliers.index') }}">
                    <a href="{{ route('admin.serviceinvoice.index') }}"><i
                            class="bi bi-record-circle"></i>@lang('Service Invoice')</a>
                </li>

                <li class="{{ menuActive('admin.serviceinvoicepayment.index') }}">
                    <a href="{{ route('admin.serviceinvoicepayment.index') }}"><i
                            class="bi bi-record-circle"></i>@lang('Service Payment')</a>
                </li>
            </ul>
        </li> --}}

        @if (Auth::guard('admin')->user()->hasPermission('admin.setting.access.customer.supplier'))
            <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.suppliers*', 'admin.customers*') }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-people"></i>
                    </div>
                    <div class="menu-title">@lang('Contacts')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.supplier.all'))
                        <li class="{{ menuActive('admin.suppliers.all') }}">
                            <a href="{{ route('admin.suppliers.all') }}"><i
                                    class="bi bi-record-circle"></i>@lang('All Suppliers')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.customer.all'))
                        <li class="{{ menuActive('admin.customers.all') }}">
                            <a href="{{ route('admin.customers.all') }}"><i
                                    class="bi bi-record-circle"></i>@lang('All Customers')</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.setting.access.account'))
            {{-- <li class="menu-label">@lang('Account Setting')</li> --}}
            <li class="sidebar-menu sidebar-dropdown"
                {{ menuActive(['admin.account*', 'admin.paymentmethod*', 'admin.transactionhistory*', 'admin.moduletype*']) }}>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-people"></i>
                    </div>
                    <div class="menu-title">@lang('Account')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.paymentmethod.list'))
                        <li class="{{ menuActive('admin.paymentmethod*') }}">
                            <a href="{{ route('admin.paymentmethod.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Payment Method')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.account.list'))
                        <li class="{{ menuActive('admin.account*') }}">
                            <a href="{{ route('admin.account.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Account')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.accounttransfer.list'))
                        <li class="{{ menuActive('admin.accounttransfer*') }}">
                            <a href="{{ route('admin.accounttransfer.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Account Balance Transfer')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.deposit.list'))
                        <li class="{{ menuActive('admin.deposit*') }}">
                            <a href="{{ route('admin.deposit.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Deposit')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.deposit.list'))
                        <li class="{{ menuActive('admin.officialloan*') }}">
                            <a href="{{ route('admin.officialloan.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Official Loan')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.officialloanpayment.list'))
                        <li class="{{ menuActive('admin.officialloanpayment*') }}">
                            <a href="{{ route('admin.officialloanpayment.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Official Loan Payment')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.withdrawal.list'))
                        <li class="{{ menuActive('admin.withdrawal*') }}">
                            <a href="{{ route('admin.withdrawal.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Withdrawal')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.customeradvance.list'))
                        <li class="{{ menuActive('admin.customeradvance*') }}">
                            <a href="{{ route('admin.customeradvance.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Customer Advance')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.customerduepayment.list'))
                        <li class="{{ menuActive('admin.customerduepayment*') }}">
                            <a href="{{ route('admin.customerduepayment.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Customer Due Payment')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.supplierduepayment.list'))
                        <li class="{{ menuActive('admin.supplierduepayment*') }}">
                            <a href="{{ route('admin.supplierduepayment.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Supplier Due Payment')
                            </a>
                        </li>
                    @endif



                    @if (Auth::guard('admin')->user()->hasPermission('admin.moduletype.list'))
                        <li class="{{ menuActive('admin.moduletype*') }}">
                            <a href="{{ route('admin.moduletype.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Module Type')
                            </a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.transactionhistory.list'))
                        <li class="{{ menuActive('admin.transactionhistory*') }}">
                            <a href="{{ route('admin.transactionhistory.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Transaction History')
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.setting.access.payroll'))
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-gear"></i>
                    </div>
                    <div class="menu-title">@lang('Payroll') </div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.department.list'))
                        <li>
                            <a href="{{ route('admin.department.index') }}"><i class="bi bi-circle"></i>
                                @lang('Department')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.employee.list'))
                        <li>
                            <a href="{{ route('admin.employee.index') }}"><i class="bi bi-circle"></i>
                                @lang('Employees')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.employee.list'))
                        <li>
                            <a href="{{ route('admin.marketer.index') }}"><i class="bi bi-circle"></i>
                                @lang('Marketers')</a>
                        </li>
                    @endif
                    
                    @if (Auth::guard('admin')->user()->hasPermission('admin.employee.list'))
                        <li>
                            <a href="{{ route('admin.distribution.index') }}"><i class="bi bi-circle"></i>
                                @lang('Distributor List')</a>
                        </li>
                    @endif



                    @if (Auth::guard('admin')->user()->hasPermission('admin.attendance.list'))
                        <li class="{{ menuActive('admin.attendance.index') }}">
                            <a href="{{ route('admin.attendance.index') }}"><i
                                    class="bi bi-record-circle"></i>@lang('Attenance List')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.salarytype.list'))
                        <li>
                            <a href="{{ route('admin.salarytype.index') }}">
                                <i class="bi bi-circle"></i> Salary Type</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.salaryadvance.list'))
                        <li>
                            <a href="{{ route('admin.salaryadvance.index') }}">
                                <i class="bi bi-circle"></i> Advance Salary</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.salarybonussetup.list'))
                        <li>
                            <a href="{{ route('admin.salarybonussetup.index') }}">
                                <i class="bi bi-circle"></i>Bonus Setup</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.loan.list'))
                        <li>
                            <a href="{{ route('admin.loan.index') }}">
                                <i class="bi bi-circle"></i> Loans</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.loan.list'))
                        <li>
                            <a href="{{ route('admin.salarydeduction.index') }}">
                                <i class="bi bi-circle"></i> Salary Deduction</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.overtimeallowance.list'))
                        <li>
                            <a href="{{ route('admin.overtimeallowance.index') }}">
                                <i class="bi bi-circle"></i> Over Time Allowance</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.salarygenerate.list'))
                        <li>
                            <a href="{{ route('admin.salarygenerate.index') }}">
                                <i class="bi bi-circle"></i> Salary Process</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.salarypaymenthistory.list'))
                        <li>
                            <a href="{{ route('admin.salarypaymenthistory.index') }}">
                                <i class="bi bi-circle"></i> Salary Payment</a>
                        </li>
                        
                        <li>
                            <a href="{{ route('admin.festivalbonus.index') }}">
                                <i class="bi bi-circle"></i> Festival Bonus</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.festivalbonusdetail.index') }}">
                                <i class="bi bi-circle"></i> Festival Bonus Detail</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.festivalbonuspayment.index') }}">
                                <i class="bi bi-circle"></i> Festival Bonus Payment</a>
                        </li>
                    @endif

                        
                </ul>
            </li>
        @endif

        {{-- <li class="menu-label">@lang('Payment')</li> --}}
        @if (Auth::guard('admin')->user()->hasPermission('admin.expenses.module'))
            <li
                class="sidebar--menu sidebar--dropdown {{ menuActive(['admin.expensecategory*', 'admin.expense*', 'admin.expensepaymenthistory']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-people"></i>
                    </div>
                    <div class="menu-title">@lang('Expenses')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.expensecategory.list'))
                        <li class="{{ menuActive('admin.expensecategory.index') }}">
                            <a href="{{ route('admin.expensecategory.index') }}"><i
                                    class="bi bi-plus-square"></i>@lang('Categories')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.expense.list'))
                        <li class="{{ menuActive('admin.expense.index') }}">
                            <a href="{{ route('admin.expense.index') }}"><i
                                    class="bi bi-circle"></i>@lang('All Expense')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.expensepaymenthistory.list'))
                        <li class="{{ menuActive('admin.expensepaymenthistory.index') }}">
                            <a href="{{ route('admin.expensepaymenthistory.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Expense Payment')</a>
                        </li>
                    @endif

                    @if (Auth::guard('admin')->user()->hasPermission('admin.assetexpense.list'))
                        <li class="{{ menuActive('admin.assetexpense.index') }}">
                            <a href="{{ route('admin.assetexpense.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Asset Expenses')</a>
                        </li>
                    @endif

                    <li class="{{ menuActive('admin.assetexpensepayment*') }}">
                        <a href="{{ route('admin.assetexpensepayment.index') }}">
                            <i class="bi bi-circle"></i>
                            @lang('Asset Expenses Payment')
                        </a>
                    </li>

                    @if (Auth::guard('admin')->user()->hasPermission('admin.monthlyexpense.list'))
                        <li class="{{ menuActive('admin.monthlyexpense.index') }}">
                            <a href="{{ route('admin.monthlyexpense.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Monthly Expenses')</a>
                        </li>
                    @endif

                    <li class="{{ menuActive('admin.monthlyexpensepayment*') }}">
                        <a href="{{ route('admin.monthlyexpensepayment.index') }}">
                            <i class="bi bi-circle"></i>
                            @lang('Monthly Expenses Payment')
                        </a>
                    </li>

                    @if (Auth::guard('admin')->user()->hasPermission('admin.transportexpense.list'))
                        <li class="{{ menuActive('admin.transportexpense.index') }}">
                            <a href="{{ route('admin.transportexpense.index') }}"><i
                                    class="bi bi-circle"></i>@lang('Transport Expenses')</a>
                        </li>
                    @endif

                    <li class="{{ menuActive('admin.transportexpensepayment*') }}">
                        <a href="{{ route('admin.transportexpensepayment.index') }}">
                            <i class="bi bi-circle"></i>
                            @lang('Transport Expenses Payment')
                        </a>
                    </li>
                </ul>
            </li>
        @endif


        @if (Auth::guard('admin')->user()->hasPermission('admin.production.module'))
            <li class="sidebar--menu sidebar--dropdown {{ menuActive(['admin.dailyproduction*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-box"></i>
                    </div>
                    <div class="menu-title">@lang('Productions')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.list'))
                        <li class="{{ menuActive('admin.dailyproduction.index') }}">
                            <a href="{{ route('admin.dailyproduction.index') }}"><i class="bi bi-plus-square"></i>
                                @lang('Daily Productions')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.list'))
                        <li class="{{ menuActive('admin.dailyproduction.entry.report') }}">
                            <a href="{{ route('admin.dailyproduction.entry.report') }}">
                                <i class="bi bi-plus-square"></i>
                                @lang('Productions Entry Reports')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.makeproduction.list'))
                        <li class="{{ menuActive('admin.makeproduction.index') }}">
                            <a href="{{ route('admin.makeproduction.index') }}"><i class="bi bi-plus-square"></i>
                                @lang('Make Productions Expenses')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.productionloss.list'))
                        <li class="{{ menuActive('admin.productionloss.index') }}">
                            <a href="{{ route('admin.productionloss.index') }}"><i class="bi bi-plus-square"></i>
                                @lang('Productions Loss')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.list'))
                        <li class="{{ menuActive('admin.dailyproduction.report') }}">
                            <a href="{{ route('admin.dailyproduction.report') }}"><i class="bi bi-plus-square"></i>
                                @lang('Productions Report')</a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.dailyproduction.productionreport'))
                        <li class="{{ menuActive('admin.dailyproductiongroup.report') }}">
                            <a href="{{ route('admin.dailyproductiongroup.report') }}"><i
                                    class="bi bi-plus-square"></i>
                                @lang('Department Production Reports')</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        @php
            $isReport = 1;
        @endphp


        @if ($isReport === 1)
            @include('admin.partials.reports_sidenav')
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.manage.user'))
            <li class="sidebar-menu sidebar-dropdown"
                {{ menuActive(['admin.staff*', 'admin.roles*', 'admin.permissions*']) }}>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-people"></i>
                    </div>
                    <div class="menu-title">@lang('Manage User')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.staff.list'))
                        <li class="{{ menuActive('admin.staff*') }}">
                            <a href="{{ route('admin.staff.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('All Staff')
                            </a>
                        </li>
                    @endif
                    @if (Auth::guard('admin')->user()->hasPermission('admin.role.list'))
                        <li class="{{ menuActive('admin.role*') }}">
                            <a href="{{ route('admin.role.index') }}">
                                <i class="bi bi-circle"></i>
                                @lang('Roles')
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (Auth::guard('admin')->user()->hasPermission('admin.setting.access'))
            <li
                class="sidebar--menu sidebar--dropdown {{ menuActive(['admin.setting.index', 'admin.setting.system*', 'admin.setting.cookie', 'admin.setting.logo.icon', 'admin.extensions', 'admin.language*', 'admin.seo', 'admin.maintenance.mode']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bi bi-globe"></i>
                    </div>
                    <div class="menu-title">@lang('Web Settings')</div>
                </a>
                <ul>
                    @if (Auth::guard('admin')->user()->hasPermission('admin.setting.general'))
                        <li class="{{ menuActive(['admin.setting.index']) }}">
                            <a href="{{ route('admin.setting.index') }}">
                                <i class="bi bi-record-circle"></i>@lang('General Setting')
                            </a>
                        </li>
                    @endif
                    {{-- <li class="{{ menuActive('admin.setting.system.configuration') }}">
                    <a href="{{ route('admin.setting.system.configuration') }}">
                        <i class="bi bi-record-circle"></i>@lang('System Configuration')
                    </a>
                </li> --}}
                    @if (Auth::guard('admin')->user()->hasPermission('admin.setting.logo'))
                        <li class="{{ menuActive('admin.setting.logo.icon') }}">
                            <a href="{{ route('admin.setting.logo.icon') }}">
                                <i class="bi bi-record-circle"></i>@lang('Logo & Favicon')</a>
                        </li>
                    @endif
                    {{-- <li class="{{ menuActive('admin.extensions.index') }}">
                    <a href="{{ route('admin.extensions.index') }}"><i
                            class="bi bi-record-circle"></i>@lang('Extensions')</a>
                </li> --}}
                    @if (Auth::guard('admin')->user()->hasPermission('admin.language.manage'))
                        <li class="{{ menuActive('admin.language.manage') }}">
                            <a href="{{ route('admin.language.manage') }}"><i
                                    class="bi bi-record-circle"></i>@lang('Language')</a>
                        </li>
                    @endif
                    {{-- <li class="{{ menuActive('admin.seo') }}">
                    <a href="{{ route('admin.seo') }}"><i class="bi bi-record-circle"></i>@lang('SEO Manager')</a>
                </li>

                <li class="{{ menuActive('admin.maintenance.mode') }}">
                    <a href="{{ route('admin.maintenance.mode') }}"><i
                            class="bi bi-record-circle"></i>@lang('Maintenance Mode')</a>
                </li>
                <li class="{{ menuActive('admin.setting.cookie') }}">
                    <a href="{{ route('admin.setting.cookie') }}"><i
                            class="bi bi-record-circle"></i>@lang('GDPR Cookie')</a>
                </li>
                <li class="{{ menuActive('admin.setting.custom.css') }}">
                    <a href="{{ route('admin.setting.custom.css') }}"><i
                            class="bi bi-record-circle"></i>@lang('Custom CSS')</a>
                </li> --}}

                </ul>
            </li>


            {{-- <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.setting.notification*') }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-bell"></i>
                </div>
                <div class="menu-title">@lang('Notification Setting')</div>
            </a>
            <ul>
                <li class="{{ menuActive('admin.setting.notification.global') }}">
                    <a href="{{ route('admin.setting.notification.global') }}"><i
                            class="bi bi-record-circle"></i>@lang('Global Template')</a>
                </li>
                <li class="{{ menuActive('admin.setting.notification.email') }}">
                    <a href="{{ route('admin.setting.notification.email') }}"><i
                            class="bi bi-record-circle"></i>@lang('Email Setting')</a>
                </li>
                <li class="{{ menuActive('admin.setting.notification.sms') }}">
                    <a href="{{ route('admin.setting.notification.sms') }}"><i
                            class="bi bi-record-circle"></i>@lang('SMS Setting')</a>
                </li>
                <li class="{{ menuActive('admin.setting.notification.templates') }}">
                    <a href="{{ route('admin.setting.notification.templates') }}"><i
                            class="bi bi-record-circle"></i>@lang('Notification Templates')</a>
                </li>
                <li class="{{ menuActive('admin.subscriber.index') }}">
                    <a href="{{ route('admin.subscriber.index') }}">
                        <i class="bi bi-bell-slash"></i>
                        @lang('Subscribers')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.support*') }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-chat-right-dots"></i>
                </div>
                <div class="menu-title">@lang('Customer Support')</div>
                @if ($pendingSupportCount)
                    <span class="red__notify"></span>
                @endif
            </a>
            <ul>
                <li class="{{ menuActive('admin.support.pending') }}">
                    <a href="{{ route('admin.support.pending') }}">
                        <i class="bi bi-record-circle"></i>@lang('Pending Support')
                        @if ($pendingSupportCount)
                            <span class="red__notify"></span>
                        @endif
                    </a>
                </li>
                <li class="{{ menuActive('admin.support.closed') }}">
                    <a href="{{ route('admin.support.closed') }}"><i
                            class="bi bi-record-circle"></i>@lang('Closed Support')</a>
                </li>
                <li class="{{ menuActive('admin.support.answered') }}">
                    <a href="{{ route('admin.support.answered') }}"><i
                            class="bi bi-record-circle"></i>@lang('Answered Support')</a>
                </li>
                <li class="{{ menuActive('admin.support.index') }}">
                    <a href="{{ route('admin.support.index') }}"><i
                            class="bi bi-record-circle"></i>@lang('All Support')</a>
                </li>
            </ul>
        </li>
        <li class="sidebar--menu {{ menuActive('admin.subscriber*') }}">
        </li>
        <li class="menu-label">@lang('Pages & Section')</li>
        <li class="sidebar--menu {{ menuActive('admin.frontend.manage.pages*') }}">
            <a href="{{ route('admin.frontend.manage.pages') }}">
                <div class="parent-icon"><i class="bi bi-file-earmark"></i>
                </div>
                <div class="menu-title">@lang('Manage Pages')</div>
            </a>
        </li>
        <li class="sidebar--menu sidebar--dropdown {{ menuActive('admin.frontend.sections*') }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bi bi-bookshelf"></i>
                </div>
                <div class="menu-title">@lang('Manage Section')</div>
            </a>
            <ul>
                @php
                    $lastSegment = collect(request()->segments())->last();
                @endphp
                @foreach (getPageSections(true) as $k => $secs)
                    @if ($secs['builder'])
                        <li class="{{ $lastSegment == $k ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.frontend.sections', $k) }}">
                                <i class="bi bi-record-circle"></i>{{ __($secs['name']) }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li> --}}
        @endif
    </ul>
</aside>

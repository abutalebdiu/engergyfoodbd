<?php

use Illuminate\Support\Facades\Route;


use App\Models\Expense\MonthlyExpense;
use App\Http\Controllers\Admin\HR\LoanController;

use App\Http\Controllers\Admin\Item\ItemController;

use App\Http\Controllers\Admin\Item\UnitController;

use App\Http\Controllers\Admin\HR\EmployeeController;
use App\Http\Controllers\Admin\HR\MarketerController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Report\AssetController;
use App\Http\Controllers\Admin\HR\AttendanceController;
use App\Http\Controllers\Admin\HR\DepartmentController;

use App\Http\Controllers\Admin\HR\SalaryTypeController;
use App\Http\Controllers\Admin\Product\StockController;

use App\Http\Controllers\Admin\HR\SalarySetupController;

use App\Http\Controllers\Admin\Item\ItemBrandController;
use App\Http\Controllers\Admin\Item\ItemOrderController;

use App\Http\Controllers\Admin\Item\ItemStockController;
use App\Http\Controllers\Admin\Order\PerchaseController;

use App\Http\Controllers\Admin\Account\AccountController;
use App\Http\Controllers\Admin\Account\DepositController;
use App\Http\Controllers\Admin\Expense\ExpenseController;

use App\Http\Controllers\Admin\HR\DistributionController;
use App\Http\Controllers\Admin\Item\ItemReturnController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\HR\FestivalBonusController;
use App\Http\Controllers\Admin\HR\SalaryAdvanceController;

use App\Http\Controllers\Admin\HR\SalaryGenerateController;
use App\Http\Controllers\Admin\Item\ItemCategoryController;
use App\Http\Controllers\Admin\Order\OrderDetailController;
use App\Http\Controllers\Admin\Order\OrderReturnController;
use App\Http\Controllers\Admin\Order\PointOfSaleController;
use App\Http\Controllers\Admin\Report\LiabilitieController;
use App\Http\Controllers\Admin\Account\ModuleTypeController;
use App\Http\Controllers\Admin\Account\SettlementController;
use App\Http\Controllers\Admin\Account\WithdrawalController;
use App\Http\Controllers\Admin\HR\SalaryDeductionController;
use App\Http\Controllers\Admin\HR\SalaryBonusSetupController;
use App\Http\Controllers\Admin\Warehouse\WarehouseController;
use App\Http\Controllers\Admin\Account\CustomerLoanController;
use App\Http\Controllers\Admin\Account\OfficialLoanController;
use App\Http\Controllers\Admin\Account\OrderPaymentController;
use App\Http\Controllers\Admin\Expense\AssetExpenseController;
use App\Http\Controllers\Admin\HR\OverTimeAllowanceController;
use App\Http\Controllers\Admin\Order\PurchaseReturnController;
use App\Http\Controllers\Admin\Product\ProductBrandController;
use App\Http\Controllers\Admin\Product\ProductStockController;
use App\Http\Controllers\Admin\Account\PaymentMethodController;
use App\Http\Controllers\Admin\Expense\ExpenseDetailController;
use App\Http\Controllers\Admin\Item\ItemOrderPaymentController;
use App\Http\Controllers\Admin\Product\ProductDamageController;
use App\Http\Controllers\Admin\Product\ProductRecipeController;
use App\Http\Controllers\Admin\Expense\MonthlyExpenseController;
use App\Http\Controllers\Admin\HR\FestivalBonusDetailController;
use App\Http\Controllers\Admin\Item\ItemReturnPaymentController;
use App\Http\Controllers\Admin\Service\ServiceInvoiceController;
use App\Http\Controllers\Admin\Account\AccountTransferController;
use App\Http\Controllers\Admin\Account\CustomerAdvanceController;
use App\Http\Controllers\Admin\Account\SupplierAdvanceController;
use App\Http\Controllers\Admin\Expense\ExpenseCategoryController;
use App\Http\Controllers\Admin\HR\FestivalBonusPaymentController;
use App\Http\Controllers\Admin\HR\SalaryPaymentHistoryController;
use App\Http\Controllers\Admin\Product\ProductCategoryController;
use App\Http\Controllers\Admin\Expense\TransportExpenseController;
use App\Http\Controllers\Admin\Warehouse\WarehouseOrderController;
use App\Http\Controllers\Admin\Order\Quotation\QuotationController;
use App\Http\Controllers\Admin\Production\MakeProductionController;
use App\Http\Controllers\Admin\Production\ProductionLossController;
use App\Http\Controllers\Admin\Account\CustomerDuePaymentController;
use App\Http\Controllers\Admin\Account\CustomerSettlementController;
use App\Http\Controllers\Admin\Account\OrderReturnPaymentController;
use App\Http\Controllers\Admin\Account\SupplierDuePaymentController;
use App\Http\Controllers\Admin\Account\TransactionHistoryController;
use App\Http\Controllers\Admin\Order\PurchaseReturnDetailController;
use App\Http\Controllers\Admin\Production\DailyProductionController;
use App\Http\Controllers\Admin\Account\CustomerLoanPaymentController;
use App\Http\Controllers\Admin\Account\OfficialLoanPaymentController;
use App\Http\Controllers\Admin\Expense\AssetExpensePaymentController;
use App\Http\Controllers\Admin\Account\OrderSupplierPaymentController;
use App\Http\Controllers\Admin\Commission\CommissionInvoiceController;
use App\Http\Controllers\Admin\Account\PurchaseReturnPaymentController;
use App\Http\Controllers\Admin\Commission\MarketerCommissionController;
use App\Http\Controllers\Admin\Expense\ExpensePaymentHistoryController;
use App\Http\Controllers\Admin\Expense\MonthlyExpensePaymentController;
use App\Http\Controllers\Admin\Product\CustomerProductDamageController;
use App\Http\Controllers\Admin\Service\ServiceInvoicePaymentController;
use App\Http\Controllers\Admin\Account\SupplierPayablePaymentController;
use App\Http\Controllers\Admin\Distributors\DistributorPaymentController;
use App\Http\Controllers\Admin\Expense\TransportExpensePaymentController;
use App\Http\Controllers\Admin\Order\Quotation\QuotationDetailController;
use App\Http\Controllers\Admin\Account\MarketerCommissionPaymentController;
use App\Http\Controllers\Admin\Distributors\DistributionCommisionController;
use App\Http\Controllers\Admin\Distributors\DistributorOrderReportController;
use App\Http\Controllers\Admin\Distributors\DistributorQuotationReportController;

Route::group(['middleware' => ['auth:web,admin']], function () {

    // Quotation Order
    Route::resource('asset', AssetController::class);
    Route::resource('liabilitie', LiabilitieController::class);
    Route::resource('quotation', QuotationController::class);
    Route::get('quotation/deleted/list', [QuotationController::class, 'trash'])->name('quotation.deleted.list');
    Route::get('quotations/restore/{id}', [QuotationController::class, 'restore'])->name('quotation.restore');
    Route::get('quotations/force-delete/{id}', [QuotationController::class, 'forceDelete'])->name('quotation.forceDelete');
    
    
    Route::resource('quotationdetail', QuotationDetailController::class);

    Route::get('quotation/invoice/print/{id}', [QuotationController::class, 'printinvoice'])->name('quotation.invoice.print');
    Route::get('quotation/challan/print/{id}', [QuotationController::class, 'printchallan'])->name('quotation.challan.print');
    Route::get('quotation/product/demand', [QuotationController::class, 'productdemand'])->name('quotation.product.demand');
 
    // Sales Order
    Route::resource('order', OrderController::class);
    Route::get('order/product/demand', [OrderController::class, 'productorder'])->name('order.product.demand');

    Route::get('sale/order/search-product', [OrderController::class, 'searchProduct'])->name('order.searchProduct');
    Route::get('sale/order/search-customer', [OrderController::class, 'searchCustomer'])->name('order.searchCustomer');
    Route::get('sale/order/search-reference', [OrderController::class, 'searchReferance'])->name('order.searchReferance');
    Route::get('sale/order/get-products', [OrderController::class, 'getProducts'])->name('order.getProducts');
    Route::get('sale/order/add-product/{id}', [OrderController::class, 'addProduct'])->name('order.addProduct');
    Route::get('sale/order/update-product/{id}', [OrderController::class, 'updateProduct'])->name('order.updateProduct');
    Route::get('sale/order/delete-product/{id}', [OrderController::class, 'removeProduct'])->name('order.deleteProduct');

    Route::get('order/product/delivery/{id}', [OrderController::class, 'deliveryorder'])->name('order.product.delivery');
    Route::get('order/invoice/print/{id}', [OrderController::class, 'printinvoice'])->name('order.invoice.print');
    Route::get('order/challan/print/{id}', [OrderController::class, 'printchallan'])->name('order.challan.print');
    Route::get('date/wise/customerorder', [OrderController::class, 'datewisecustomerorder'])->name('order.date.customerorder');
    
    Route::resource('orderdetail', OrderDetailController::class);
    Route::resource('orderpayment', OrderPaymentController::class);
    Route::resource('ordersupplierpayment', OrderSupplierPaymentController::class);
    Route::resource('orderreturn', OrderReturnController::class);
    Route::resource('orderreturnpayment', OrderReturnPaymentController::class);

    Route::get('order/pos/create', [PointOfSaleController::class, 'create'])->name('order.pos.create');
    Route::post('order/pos/store', [PointOfSaleController::class, 'store'])->name('order.pos.store');
    Route::get('order/pos/edit/{id}', [PointOfSaleController::class, 'edit'])->name('order.pos.edit');
    Route::post('order/pos/update/{id}', [PointOfSaleController::class, 'update'])->name('order.pos.update');

    // purchase route
    Route::group(['prefix' => 'purchase', 'as' => 'purchase.'], function () {
        Route::get('index', [PerchaseController::class, 'index'])->name('index');
        Route::get('create', [PerchaseController::class, 'create'])->name('create');
        Route::post('store', [PerchaseController::class, 'store'])->name('store');
        Route::get('show/{id}', [PerchaseController::class, 'show'])->name('show');
        Route::get('edit/{id}', [PerchaseController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [PerchaseController::class, 'update'])->name('update');
        Route::delete('destroy/{id}', [PerchaseController::class, 'destroy'])->name('destroy');

        Route::get('product/delivery/{id}', [PerchaseController::class, 'deliveryorder'])->name('product.delivery');
        Route::get('invoice/print/{id}', [PerchaseController::class, 'printinvoice'])->name('invoice.print');
    });

    Route::get('purchase/search-product', [PerchaseController::class, 'searchProduct'])->name('purchase.searchProduct');
    Route::get('purchase/search-supplier', [PerchaseController::class, 'searchSupplier'])->name('purchase.searchSupplier');
    Route::get('purchase/get-products', [PerchaseController::class, 'getProducts'])->name('purchase.getProducts');
    Route::get('purchase/add-product/{id}', [PerchaseController::class, 'addProduct'])->name('purchase.addProduct');
    Route::get('purchase/update-product/{id}', [PerchaseController::class, 'updateProduct'])->name('purchase.updateProduct');
    Route::get('purchase/delete-product/{id}', [PerchaseController::class, 'removeProduct'])->name('purchase.deleteProduct');

    Route::resource('purchasereturn', PurchaseReturnController::class);
    Route::resource('purchasereturnDetail', PurchaseReturnDetailController::class);
    Route::resource('purchasereturnpayment', PurchaseReturnPaymentController::class);



    // HR

    // Payroll
    Route::resource('employee', App\Http\Controllers\Admin\HR\EmployeeController::class);
    
    Route::get('employee/status/change/{id}', [App\Http\Controllers\Admin\HR\EmployeeController::class, 'status'])->name('employee.status.change');
    Route::get('employee/payment/history/{id}', [App\Http\Controllers\Admin\HR\EmployeeController::class, 'paymenthistory'])->name('employee.payment.history');
    Route::get('employee/payment/history/pdf/{id}', [App\Http\Controllers\Admin\HR\EmployeeController::class, 'paymenthistorypdf'])->name('employee.payment.history.pdf');
    Route::resource('salarytype', App\Http\Controllers\Admin\HR\SalaryTypeController::class);
    Route::resource('salaryadvance', App\Http\Controllers\Admin\HR\SalaryAdvanceController::class);
    Route::resource('salarybonussetup', App\Http\Controllers\Admin\HR\SalaryBonusSetupController::class);
    Route::resource('loan', App\Http\Controllers\Admin\HR\LoanController::class);
    Route::resource('salarydeduction', App\Http\Controllers\Admin\HR\SalaryDeductionController::class);
    Route::resource('salarysetup', App\Http\Controllers\Admin\HR\SalarySetupController::class);
    Route::resource('salarygenerate', App\Http\Controllers\Admin\HR\SalaryGenerateController::class);
    Route::get('salarygenerate/show/detail', [App\Http\Controllers\Admin\HR\SalaryGenerateController::class, 'show'])->name('salarygenerate.show.detail');
    Route::resource('salarypaymenthistory', App\Http\Controllers\Admin\HR\SalaryPaymentHistoryController::class);
    Route::get('salarypayment/single/payment', [App\Http\Controllers\Admin\HR\SalaryPaymentHistoryController::class, 'singleemployeesalary'])->name('salarypayment.single.employee.salary');
    Route::get('salarypayment/single/get-unpaid-salary', [App\Http\Controllers\Admin\HR\SalaryPaymentHistoryController::class, 'getUnpaidSalary'])->name('hr.get_unpaid_salary');
    Route::post('salarypayment/single/payment/store', [App\Http\Controllers\Admin\HR\SalaryPaymentHistoryController::class, 'singlestore'])->name('salarypayment.single.employee.salary.store');
    Route::resource('department', App\Http\Controllers\Admin\HR\DepartmentController::class);
    Route::resource('marketer', App\Http\Controllers\Admin\HR\MarketerController::class);
    Route::get('marketer/status/change/{id}', [App\Http\Controllers\Admin\HR\MarketerController::class, 'status'])->name('marketer.status.change');

    // Bonus

    Route::resource('festivalbonus', App\Http\Controllers\Admin\HR\FestivalBonusController::class);
    Route::resource('festivalbonusdetail', App\Http\Controllers\Admin\HR\FestivalBonusDetailController::class);
    Route::resource('festivalbonuspayment', App\Http\Controllers\Admin\HR\FestivalBonusPaymentController::class);

    // Report
    Route::get('salary/department/wise', [App\Http\Controllers\Admin\HR\SalaryGenerateController::class, 'getDepartmentWiseSalaries'])->name('salary.departmentwise');

    // Expense
    Route::resource('expensecategory', ExpenseCategoryController::class);
    Route::resource('expense', ExpenseController::class);
    Route::resource('expensedetail', ExpenseDetailController::class);
    Route::resource('expensepaymenthistory', ExpensePaymentHistoryController::class);
    Route::resource('transportexpense', TransportExpenseController::class);
    Route::resource('monthlyexpense', MonthlyExpenseController::class);
    Route::resource('assetexpense', AssetExpenseController::class);


    // payment

    Route::resource('assetexpensepayment', AssetExpensePaymentController::class);
    Route::resource('transportexpensepayment', TransportExpensePaymentController::class);
    Route::resource('monthlyexpensepayment', MonthlyExpensePaymentController::class);

    // Salary Management
    Route::resource('attendance', AttendanceController::class);

    // Account
    Route::resource('paymentmethod', PaymentMethodController::class);
    Route::resource('account', AccountController::class);
    Route::get('account/dayclosed/reports', [AccountController::class, 'dayclosereport'])->name('account.dayclosed');
    Route::resource('deposit', DepositController::class);
    Route::resource('withdrawal', WithdrawalController::class);
    Route::resource('moduletype', ModuleTypeController::class);
    Route::resource('transactionhistory', TransactionHistoryController::class);
    Route::resource('accounttransfer', AccountTransferController::class);
    Route::resource('customeradvance', CustomerAdvanceController::class);
    Route::resource('customerduepayment', CustomerDuePaymentController::class);
    Route::resource('supplieradvance', SupplierAdvanceController::class);
    Route::resource('supplierduepayment', SupplierDuePaymentController::class);
    Route::resource('officialloan', OfficialLoanController::class);
    Route::resource('officialloanpayment', OfficialLoanPaymentController::class);
    Route::resource('overtimeallowance', OverTimeAllowanceController::class);
    Route::resource('customersettlement', CustomerSettlementController::class);
    Route::resource('customerloan', CustomerLoanController::class);
    Route::resource('customerloanpayment', CustomerLoanPaymentController::class);

    // Service Charge
    Route::resource('serviceinvoice', App\Http\Controllers\Admin\Service\ServiceInvoiceController::class);
    Route::resource('serviceinvoicepayment', App\Http\Controllers\Admin\Service\ServiceInvoicePaymentController::class);

    // Warehouse
    Route::resource('productcategory', ProductCategoryController::class);
    Route::resource('productbrand', ProductBrandController::class);
    Route::resource('product', ProductController::class);
    Route::get('product/status/{id}', [ProductController::class, 'status'])->name('product.status');
    Route::get('product/sales/{id}', [ProductController::class, 'sales'])->name('product.sales');
    Route::get('product/production/{id}', [ProductController::class, 'production'])->name('product.production');
    Route::get('product/customer/price/{id}', [ProductController::class, 'customerprice'])->name('product.customer.price');
    Route::post('product/customer/price/update', [ProductController::class, 'customerpriceupdate'])->name('product.customer.price.update');
    Route::post('product/upload', [ProductController::class, 'productupload'])->name('product.upload');
    Route::resource('productstock', ProductStockController::class);
    Route::resource('productdamage', ProductDamageController::class);
    Route::resource('customerproductdamage', CustomerProductDamageController::class);
    Route::resource('stock', StockController::class);
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('warehouseorder', WarehouseOrderController::class);
    Route::resource('productrecipe', ProductRecipeController::class);


    // customer due payment
    Route::post('customer/due/payment/{id}', [CustomerDuePaymentController::class, 'duePayment'])->name('customer.due.payment');
    // supplier payment
    Route::post('supplier/payable/payment/{id}', [SupplierPayablePaymentController::class, 'payablePayment'])->name('supplier.payable.payment');

    Route::group(['prefix' => 'items', 'as' => 'items.'], function () {

        // unit
        Route::group(['prefix' => 'unit', 'as' => 'unit.'], function () {
            Route::get('index', [UnitController::class, 'index'])->name('index');
            Route::get('create/{id?}', [UnitController::class, 'create'])->name('create');
            Route::post('store/{id?}', [UnitController::class, 'store'])->name('store');
            Route::delete('destroy/{id}', [UnitController::class, 'destroy'])->name('destroy');
            Route::get('status/{id}', [UnitController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'item-category', 'as' => 'itemCategory.'], function () {
            Route::get('index', [ItemCategoryController::class, 'index'])->name('index');
            Route::get('create/{id?}', [ItemCategoryController::class, 'create'])->name('create');
            Route::post('store/{id?}', [ItemCategoryController::class, 'store'])->name('store');
            Route::get('destroy/{id}', [ItemCategoryController::class, 'destroy'])->name('destroy');
            Route::get('status/{id}', [ItemCategoryController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'item-brand', 'as' => 'itemBrand.'], function () {
            Route::get('index', [ItemBrandController::class, 'index'])->name('index');
            Route::get('create/{id?}', [ItemBrandController::class, 'create'])->name('create');
            Route::post('store/{id?}', [ItemBrandController::class, 'store'])->name('store');
            Route::get('destroy/{id}', [ItemBrandController::class, 'destroy'])->name('destroy');
            Route::get('status/{id}', [ItemBrandController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'item', 'as' => 'item.'], function () {
            Route::get('index', [ItemController::class, 'index'])->name('index');
            Route::get('create/{id?}', [ItemController::class, 'create'])->name('create');
            Route::post('store/{id?}', [ItemController::class, 'store'])->name('store');
            Route::delete('destroy/{id}', [ItemController::class, 'destroy'])->name('destroy');
            Route::get('status/{id}', [ItemController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'item-order', 'as' => 'itemOrder.'], function () {
            Route::get('index', [ItemOrderController::class, 'index'])->name('index');
            Route::get('create/{id?}', [ItemOrderController::class, 'create'])->name('create');
            Route::post('store/{id?}', [ItemOrderController::class, 'store'])->name('store');
            Route::post('update/{id?}', [ItemOrderController::class, 'update'])->name('update');
            Route::delete('destroy/{id}', [ItemOrderController::class, 'destroy'])->name('destroy');
            Route::get('status/{id}', [ItemOrderController::class, 'status'])->name('status');

            Route::get('show/{id}', [ItemOrderController::class, 'show'])->name('show');
            Route::get('edit/{id}', [ItemOrderController::class, 'edit'])->name('edit');

            Route::delete('item-order/destroy/{id}', [ItemOrderController::class, 'itemOrderDetailDestroy'])->name('itemOrderDetail.destroy');
            Route::get('invoice/print/{id}', [ItemOrderController::class, 'printinvoice'])->name('invoice.print');
            Route::get('search-product', [ItemOrderController::class, 'searchProduct'])->name('searchProduct');
            Route::get('search-supplier', [ItemOrderController::class, 'searchSupplier'])->name('searchSupplier');
            Route::get('get-products', [ItemOrderController::class, 'getProducts'])->name('getProducts');
            Route::get('add-product/{id}', [ItemOrderController::class, 'addProduct'])->name('addProduct');
            Route::get('update-product/{id}', [ItemOrderController::class, 'updateProduct'])->name('updateProduct');
            Route::get('delete-product/{id}', [ItemOrderController::class, 'removeProduct'])->name('deleteProduct');
        });
    });

    Route::resource('itemorderpayment', ItemOrderPaymentController::class);
    Route::resource('itemreturn', ItemReturnController::class);
    Route::resource('itemtstock', ItemStockController::class);
    Route::resource('itemreturnpayment', ItemReturnPaymentController::class);

    // commission
    Route::resource('commissioninvoice',  CommissionInvoiceController::class);
    Route::get('commissioninvoice/invoice/print/{id}', [CommissionInvoiceController::class, 'printinvoice'])->name('commissioninvoice.invoice.print');
    Route::resource('marketercommission', MarketerCommissionController::class);
    Route::get('marketercommission/invoice/print/{id}', [MarketerCommissionController::class, 'printinvoice'])->name('marketercommission.invoice.print');
    Route::resource('marketercommissionpayment', MarketerCommissionPaymentController::class);

    // Productions
    Route::resource('dailyproduction', DailyProductionController::class);
    Route::get('dailyproduction/entry/report', [DailyProductionController::class, 'entryreport'])->name('dailyproduction.entry.report');
    Route::resource('makeproduction', MakeProductionController::class);
    Route::resource('productionloss', ProductionLossController::class);

    Route::get('daily/production/report', [DailyProductionController::class, 'productionreport'])->name('dailyproduction.report');
    Route::get('daily/production-group/report', [DailyProductionController::class, 'productiongroupreport'])->name('dailyproductiongroup.report');
    
    
    Route::resource('distribution', DistributionController::class);
    Route::get('distribution/status/change/{id}', [DistributionController::class, 'status'])->name('distribution.status.change');
    Route::get('distribution/{distribution}/statement', [DistributionController::class, 'statement'])->name('distribution.statement');

    Route::group(['prefix' => 'distributioncommission', 'as' => 'distributioncommission.'], function () {
        Route::get('index/{id}', [DistributionCommisionController::class, 'index'])->name('index');
        Route::post('update/{id}', [DistributionCommisionController::class, 'referenceCommisionUpdate'])->name('update');
        Route::get('pdf/{id}', [DistributionCommisionController::class, 'referenceCommisionpdf'])->name('pdf');
    });


    Route::group(['prefix' => 'distributor-quotations', 'as' => 'distributor-quotations.'], function () {
        Route::get('/', [DistributorQuotationReportController::class, 'index'])->name('index');
        Route::get('show', [DistributorQuotationReportController::class, 'show'])->name('show');
    });


    Route::group(['prefix' => 'distributor-orders', 'as' => 'distributor-orders.'], function () {
        Route::get('/', [DistributorOrderReportController::class, 'index'])->name('index');
        Route::get('show', [DistributorOrderReportController::class, 'show'])->name('show');
        Route::get('show-pdf', [DistributorOrderReportController::class, 'downloadShowPdf'])->name('downloadShowPdf');
    });

    Route::group(['prefix' => 'distributor-payments', 'as' => 'distributor-payments.'], function () {
        Route::get('/', [DistributorPaymentController::class, 'index'])->name('index');
        Route::get('exportPdf', [DistributorPaymentController::class, 'exportPdf'])->name('exportPdf');
    });
    
});

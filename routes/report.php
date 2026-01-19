<?php

use App\Http\Controllers\Admin\Report\CustomerSupplierController;
use App\Http\Controllers\Admin\Report\ExpenseReportController;
use App\Http\Controllers\Admin\Report\ProductPurcheseReportControlller;
use App\Http\Controllers\Admin\Report\ProductSellReportController;
use App\Http\Controllers\Admin\Report\ProfitLossController;
use App\Http\Controllers\Admin\Report\PurcheseSaleController;
use App\Http\Controllers\Admin\Report\RegisterReportController;
use App\Http\Controllers\Admin\Report\DailyReportController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\StockAdjustmentController;
use App\Http\Controllers\Admin\Report\StockReportController;
use App\Http\Controllers\Admin\Report\ItemStockReportController;
use App\Http\Controllers\Admin\Report\DailyItemReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/profit-loss', [ProfitLossController::class, 'profitloss'])->name('profitloss');
    Route::get('/purchase-sell', [PurcheseSaleController::class, 'purchasesell'])->name('purchasesell');
    Route::get('/customer-supplier', [CustomerSupplierController::class, 'customersupplier'])->name('customersupplier');
    Route::get('/stock-report', [StockReportController::class, 'stockreport'])->name('stockreport');
    Route::get('/item-stock-report', [ItemStockReportController::class, 'itemstockreport'])->name('itemstockreport');
    Route::get('/stock-adjustment-report', [StockAdjustmentController::class, 'stockadjustment'])->name('stockadjustment');
    Route::get('/product-purchase-report', [ProductPurcheseReportControlller::class, 'productpurchasereport'])->name('productpurchasereport');
    Route::get('/product-sales-report', [ProductSellReportController::class, 'productsalesreport'])->name('productsalesreport');
    Route::get('/expense-report', [ExpenseReportController::class, 'expensereport'])->name('expensereport');
    Route::get('/register-report', [RegisterReportController::class, 'registerreport'])->name('registerreport');


    Route::get('/dailyreports', [ReportController::class, 'dailyreports'])->name('dailyreports');
    Route::post('/dailyreports/store', [DailyReportController::class, 'store'])->name('dailyreports.store');
    Route::get('/dailyarchive', [ReportController::class, 'dailyarchive'])->name('dailyarchive');
    Route::get('/summery', [ReportController::class, 'summery'])->name('summery');
    Route::get('/summery-report', [ReportController::class, 'summeryReport'])->name('summeryreport');
    Route::get('/trialbalance', [ReportController::class, 'trialbalance'])->name('trialbalance');
    Route::get('/monthly/cash/register', [ReportController::class, 'cashregister'])->name('cashregister');
    Route::get('/balancesheets', [ReportController::class, 'balancesheets'])->name('balancesheets');
    Route::get('customers/daily/sales/reports', [ReportController::class, 'customerdailyreports'])->name('customerdailyreports');
    Route::get('daily-reports', [DailyReportController::class, 'dailyReport'])->name('daily-reports');
    Route::get('daily-item-reports', [DailyItemReportController::class, 'dailyItemReport'])->name('daily-item-reports');
});

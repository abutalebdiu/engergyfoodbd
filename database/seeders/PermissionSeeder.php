<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\Setting\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissionGroups = [
            [
                'name' => 'Dashboard',
                'controller' => 'DashboardController',
                'code' => 'admin.dashboard',
            ],
            [
                'name' => 'Dashboard Statistics',
                'controller' => 'DashboardController',
                'code' => 'admin.dashboard.statistics',
            ],
            [
                'name' => 'Dashboard Chart Reports',
                'controller' => 'DashboardController',
                'code' => 'admin.dashboard.chart-reports',
            ],
            [
                'name' => 'Profile',
                'controller' => 'ProfileController',
                'code' => 'admin.profile',
            ],
            [
                'name' => 'Profile Update',
                'controller' => 'ProfileController',
                'code' => 'admin.profile.update',
            ],
            [
                'name' => 'Password',
                'controller' => 'PasswordController',
                'code' => 'admin.password',
            ],
            [
                'name' => 'Password',
                'controller' => 'PasswordController',
                'code' => 'admin.password.update',
            ],


            // Quotation

            [
                'name' => 'Quotation Lists',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.list',
            ],
            [
                'name' => 'Quotation Create',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.create',
            ],
            [
                'name' => 'Quotation Edit',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.edit',
            ],
            [
                'name' => 'Quotation Update',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.update',
            ],
            [
                'name' => 'Quotation Delete',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.destroy',
            ],
            [
                'name' => 'Quotation Show',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.show',
            ],
            [
                'name' => 'Print Invoice',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.printinvoice',
            ],
            [
                'name' => 'Print Print Challan',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.printchallan',
            ],
            [
                'name' => 'Print Product Demand',
                'controller' => 'QuotationController',
                'code' => 'admin.quotation.productdemand',
            ],


            // Quotation Details

            [
                'name' => 'Quotation Details Lists',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.list',
            ],
            [
                'name' => 'Quotation Details Create',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.create',
            ],
            [
                'name' => 'Quotation Details Store',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.store',
            ],
            [
                'name' => 'Quotation Details Edit',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.edit',
            ],
            [
                'name' => 'Quotation Details Update',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.update',
            ],
            [
                'name' => 'Quotation Details Show',
                'controller' => 'QuotationDetailController',
                'code' => 'admin.quotationdetail.show',
            ],

            // Order
            [
                'name' => 'Order Lists',
                'controller' => 'OrderController',
                'code' => 'admin.order.list',
            ],
            [
                'name' => 'Order Create',
                'controller' => 'OrderController',
                'code' => 'admin.order.create',
            ],
            [
                'name' => 'Order Store',
                'controller' => 'OrderController',
                'code' => 'admin.order.store',
            ],
            [
                'name' => 'Order Edit',
                'controller' => 'OrderController',
                'code' => 'admin.order.edit',
            ],
            [
                'name' => 'Order Update',
                'controller' => 'OrderController',
                'code' => 'admin.order.update',
            ],
            [
                'name' => 'Order Delete',
                'controller' => 'OrderController',
                'code' => 'admin.order.destroy',
            ],
            [
                'name' => 'Order Print Invoice',
                'controller' => 'OrderController',
                'code' => 'admin.order.printinvoice',
            ],
            [
                'name' => 'Order Print Challan',
                'controller' => 'OrderController',
                'code' => 'admin.order.printchallan',
            ],
            [
                'name' => 'Order Product Order',
                'controller' => 'OrderController',
                'code' => 'admin.order.productorder',
            ],

            // Order Details

            [
                'name' => 'Order Details Lists',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.list',
            ],
            [
                'name' => 'Order Details Create',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.create',
            ],
            [
                'name' => 'Order Details Store',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.store',
            ],
            [
                'name' => 'Order Details Edit',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.edit',
            ],
            [
                'name' => 'Order Details Update',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.update',
            ],
            [
                'name' => 'Order Details Show',
                'controller' => 'OrderDetailController',
                'code' => 'admin.orderdetail.show',
            ],


            // Order Payments

            [
                'name' => 'Order Payment Lists',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.list',
            ],
            [
                'name' => 'Order Payment Create',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.create',
            ],
            [
                'name' => 'Order Payment Store',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.store',
            ],
            [
                'name' => 'Order Payment Edit',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.edit',
            ],
            [
                'name' => 'Order Payment Update',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.update',
            ],
            [
                'name' => 'Order Payment Show',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.show',
            ],
            [
                'name' => 'Order Payment Delete',
                'controller' => 'OrderPaymentController',
                'code' => 'admin.orderpayment.destroy',
            ],


            // OrderSupplierPaymentController

            [
                'name' => 'Order Supplier Payment Lists',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.list',
            ],
            [
                'name' => 'Order Supplier Payment Create',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.create',
            ],
            [
                'name' => 'Order Supplier Payment Store',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.store',
            ],
            [
                'name' => 'Order Supplier Payment Edit',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.edit',
            ],
            [
                'name' => 'Order Supplier Payment Update',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.update',
            ],
            [
                'name' => 'Order Supplier Payment Show',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.show',
            ],
            [
                'name' => 'Order Supplier Payment Delete',
                'controller' => 'OrderSupplierPaymentController',
                'code' => 'admin.ordersupplierpayment.destroy',
            ],


            // OrderReturnController

            [
                'name' => 'Order Return Lists',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.list',
            ],
            [
                'name' => 'Order Return Create',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.create',
            ],
            [
                'name' => 'Order Return Store',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.store',
            ],
            [
                'name' => 'Order Return Show',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.show',
            ],
            [
                'name' => 'Order Return Edit',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.edit',
            ],
            [
                'name' => 'Order Return Update',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.update',
            ],
            [
                'name' => 'Order Return Delete',
                'controller' => 'OrderReturnController',
                'code' => 'admin.orderreturn.destroy',
            ],


            // OrderReturnPaymentController

            [
                'name' => 'Order Return Payment Lists',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.list',
            ],
            [
                'name' => 'Order Return Payment Create',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.create',
            ],
            [
                'name' => 'Order Return Payment Store',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.store',
            ],
            [
                'name' => 'Order Return Payment Show',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.show',
            ],
            [
                'name' => 'Order Return Payment Edit',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.edit',
            ],
            [
                'name' => 'Order Return Payment Update',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.update',
            ],
            [
                'name' => 'Order Return Payment Delete',
                'controller' => 'OrderReturnPaymentController',
                'code' => 'admin.orderreturnpayment.destroy',
            ],

            // CommissionInvoiceController

            [
                'name' => 'Commission Invoice Lists',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.list',
            ],
            [
                'name' => 'Commission Invoice Create',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.create',
            ],
            [
                'name' => 'Commission Invoice Store',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.store',
            ],
            [
                'name' => 'Commission Invoice Show',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.show',
            ],
            [
                'name' => 'Commission Invoice Edit',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.edit',
            ],
            [
                'name' => 'Commission Invoice Update',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.update',
            ],
            [
                'name' => 'Commission Invoice Delete',
                'controller' => 'CommissionInvoiceController',
                'code' => 'admin.commissioninvoice.destroy',
            ],

            // UnitController
            [
                'name' => 'Unit Lists',
                'controller' => 'UnitController',
                'code' => 'admin.unit.list',
            ],
            [
                'name' => 'Unit Create',
                'controller' => 'UnitController',
                'code' => 'admin.unit.create',
            ],
            [
                'name' => 'Unit Store',
                'controller' => 'UnitController',
                'code' => 'admin.unit.store',
            ],
            [
                'name' => 'Unit Show',
                'controller' => 'UnitController',
                'code' => 'admin.unit.show',
            ],
            [
                'name' => 'Unit Edit',
                'controller' => 'UnitController',
                'code' => 'admin.unit.edit',
            ],
            [
                'name' => 'Unit Update',
                'controller' => 'UnitController',
                'code' => 'admin.unit.update',
            ],
            [
                'name' => 'Unit Delete',
                'controller' => 'UnitController',
                'code' => 'admin.unit.destroy',
            ],

            // ItemCategoryController
            [
                'name' => 'Item Category Lists',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.list',
            ],
            [
                'name' => 'Item Category Create',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.create',
            ],
            [
                'name' => 'Item Category Store',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.store',
            ],
            [
                'name' => 'Item Category Show',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.show',
            ],
            [
                'name' => 'Item Category Edit',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.edit',
            ],
            [
                'name' => 'Item Category Update',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.update',
            ],
            [
                'name' => 'Item Category Delete',
                'controller' => 'ItemCategoryController',
                'code' => 'admin.itemcategory.destroy',
            ],

            // ItemBrandController
            [
                'name' => 'Item Brand Lists',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.list',
            ],
            [
                'name' => 'Item Brand Create',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.create',
            ],
            [
                'name' => 'Item Brand Store',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.store',
            ],
            [
                'name' => 'Item Brand Show',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.show',
            ],
            [
                'name' => 'Item Brand Edit',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.edit',
            ],
            [
                'name' => 'Item Brand Update',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.update',
            ],
            [
                'name' => 'Item Brand Delete',
                'controller' => 'ItemBrandController',
                'code' => 'admin.itembrand.destroy',
            ],

            // ItemController
            [
                'name' => 'Item Lists',
                'controller' => 'ItemController',
                'code' => 'admin.item.list',
            ],
            [
                'name' => 'Item Create',
                'controller' => 'ItemController',
                'code' => 'admin.item.create',
            ],
            [
                'name' => 'Item Store',
                'controller' => 'ItemController',
                'code' => 'admin.item.store',
            ],
            [
                'name' => 'Item Show',
                'controller' => 'ItemController',
                'code' => 'admin.item.show',
            ],
            [
                'name' => 'Item Edit',
                'controller' => 'ItemController',
                'code' => 'admin.item.edit',
            ],
            [
                'name' => 'Item Update',
                'controller' => 'ItemController',
                'code' => 'admin.item.update',
            ],
            [
                'name' => 'Item Delete',
                'controller' => 'ItemController',
                'code' => 'admin.item.destroy',
            ],

            // ItemOrderController
            [
                'name' => 'Item Order Lists',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.list',
            ],
            [
                'name' => 'Item Order Create',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.create',
            ],
            [
                'name' => 'Item Order Store',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.store',
            ],
            [
                'name' => 'Item Order Show',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.show',
            ],
            [
                'name' => 'Item Order Edit',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.edit',
            ],
            [
                'name' => 'Item Order Update',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.update',
            ],
            [
                'name' => 'Item Order Delete',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.destroy',
            ],
            [
                'name' => 'Item Order Detail Destroy',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.detail.destroy',
            ],
            [
                'name' => 'Item Order Print Invoice',
                'controller' => 'ItemOrderController',
                'code' => 'admin.itemorder.printinvoice',
            ],


            // ItemOrderPaymentController
            [
                'name' => 'Item Order Payment Lists',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.list',
            ],
            [
                'name' => 'Item Order Payment Create',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.create',
            ],
            [
                'name' => 'Item Order Payment Store',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.store',
            ],
            [
                'name' => 'Item Order Payment Show',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.show',
            ],
            [
                'name' => 'Item Order Payment Edit',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.edit',
            ],
            [
                'name' => 'Item Order Payment Update',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.update',
            ],
            [
                'name' => 'Item Order Payment Delete',
                'controller' => 'ItemOrderPaymentController',
                'code' => 'admin.itemorderpayment.destroy',
            ],

            // ItemReturnController

            [
                'name' => 'Item Return Lists',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.list',
            ],
            [
                'name' => 'Item Return Create',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.create',
            ],
            [
                'name' => 'Item Return Store',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.store',
            ],
            [
                'name' => 'Item Return Show',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.show',
            ],
            [
                'name' => 'Item Return Edit',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.edit',
            ],
            [
                'name' => 'Item Return Update',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.update',
            ],
            [
                'name' => 'Item Return Delete',
                'controller' => 'ItemReturnController',
                'code' => 'admin.itemreturn.destroy',
            ],

            // ItemReturnPaymentController

            [
                'name' => 'Item Return Payment Lists',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.list',
            ],
            [
                'name' => 'Item Return Payment Create',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.create',
            ],
            [
                'name' => 'Item Return Payment Store',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.store',
            ],
            [
                'name' => 'Item Return Payment Show',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.show',
            ],
            [
                'name' => 'Item Return Payment Edit',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.edit',
            ],
            [
                'name' => 'Item Return Payment Update',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.update',
            ],
            [
                'name' => 'Item Return Payment Delete',
                'controller' => 'ItemReturnPaymentController',
                'code' => 'admin.itemreturnpayment.destroy',
            ],


            // PointOfSaleController

            [
                'name' => 'Point Of Sale Lists',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.list',
            ],
            [
                'name' => 'Point Of Sale Create',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.create',
            ],
            [
                'name' => 'Point Of Sale Store',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.store',
            ],
            [
                'name' => 'Point Of Sale Show',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.show',
            ],
            [
                'name' => 'Point Of Sale Edit',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.edit',
            ],
            [
                'name' => 'Point Of Sale Update',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.update',
            ],

            [
                'name' => 'Point Of Sale Delete',
                'controller' => 'PointOfSaleController',
                'code' => 'admin.pointofsale.destroy',
            ],

            // PerchaseController

            [
                'name' => 'Perchase Lists',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.list',
            ],
            [
                'name' => 'Perchase Create',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.create',
            ],
            [
                'name' => 'Perchase Store',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.store',
            ],
            [
                'name' => 'Perchase Show',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.show',
            ],
            [
                'name' => 'Perchase Edit',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.edit',
            ],
            [
                'name' => 'Perchase Update',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.update',
            ],

            [
                'name' => 'Perchase Delete',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.destroy',
            ],

            [
                'name' => 'Perchase Delivery Order',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.product.delivery',
            ],

            [
                'name' => 'Perchase Print Invoice',
                'controller' => 'PerchaseController',
                'code' => 'admin.perchase.invoice.print',
            ],

            // PurchaseReturnController

            [
                'name' => 'Purchase Return Lists',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.list',
            ],
            [
                'name' => 'Purchase Return Create',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.create',
            ],
            [
                'name' => 'Purchase Return Store',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.store',
            ],
            [
                'name' => 'Purchase Return Show',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.show',
            ],
            [
                'name' => 'Purchase Return Edit',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.edit',
            ],
            [
                'name' => 'Purchase Return Update',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.update',
            ],

            [
                'name' => 'Purchase Return Delete',
                'controller' => 'PurchaseReturnController',
                'code' => 'admin.purchasereturn.destroy',
            ],

            // PurchaseReturnDetailController

            [
                'name' => 'Purchase Return Detail Lists',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.list',
            ],
            [
                'name' => 'Purchase Return Detail Create',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.create',
            ],
            [
                'name' => 'Purchase Return Detail Store',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.store',
            ],
            [
                'name' => 'Purchase Return Detail Show',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.show',
            ],
            [
                'name' => 'Purchase Return Detail Edit',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.edit',
            ],
            [
                'name' => 'Purchase Return Detail Update',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.update',
            ],
            [
                'name' => 'Purchase Return Detail Delete',
                'controller' => 'PurchaseReturnDetailController',
                'code' => 'admin.purchasereturndetail.destroy',
            ],

            // PurchaseReturnPaymentController

            [
                'name' => 'Purchase Return Payment Lists',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.list',
            ],
            [
                'name' => 'Purchase Return Payment Create',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.create',
            ],
            [
                'name' => 'Purchase Return Payment Store',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.store',
            ],
            [
                'name' => 'Purchase Return Payment Show',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.show',
            ],
            [
                'name' => 'Purchase Return Payment Edit',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.edit',
            ],
            [
                'name' => 'Purchase Return Payment Update',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.update',
            ],
            [
                'name' => 'Purchase Return Payment Delete',
                'controller' => 'PurchaseReturnPaymentController',
                'code' => 'admin.purchasereturnpayment.destroy',
            ],

            // Roles
            [
                'name' => 'Role Lists',
                'controller' => 'RolesController',
                'code' => 'admin.role.list',
            ],
            [
                'name' => 'Role Create',
                'controller' => 'RolesController',
                'code' => 'admin.role.create',
            ],
            [
                'name' => 'Role Edit',
                'controller' => 'RolesController',
                'code' => 'admin.role.edit',
            ],
            [
                'name' => 'Role Update',
                'controller' => 'RolesController',
                'code' => 'admin.role.update',
            ],
            [
                'name' => 'Role Delete',
                'controller' => 'RolesController',
                'code' => 'admin.role.destroy',
            ],
            [
                'name' => 'Role View',
                'controller' => 'RolesController',
                'code' => 'admin.role.show',
            ],




            // ManageSupplierController
            [
                'name' => 'Supplier All',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.all',
            ],
            [
                'name' => 'Supplier Create',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.create',
            ],
            [
                'name' => 'Supplier Store',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.store',
            ],
            [
                'name' => 'Supplier Active',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.active',
            ],
            [
                'name' => 'Supplier Banned',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.banned',
            ],
            [
                'name' => 'Supplier Detail',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.detail',
            ],
            [
                'name' => 'Supplier Update',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.update',
            ],
            [
                'name' => 'Supplier Statement',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.statement',
            ],
            [
                'name' => 'Supplier Notification Single',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.notification.single',
            ],
            [
                'name' => 'Supplier Show Notification All Form',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.showNotificationAllForm',
            ],
            [
                'name' => 'Supplier Send Notification All',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.sendNotificationAll',
            ],
            [
                'name' => 'Supplier List',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.list',
            ],
            [
                'name' => 'Supplier Notification Log',
                'controller' => 'ManageSupplierController',
                'code' => 'admin.supplier.notificationLog',
            ],


            // ManageCustomerController
            [
                'name' => 'Customer All',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.all',
            ],
            [
                'name' => 'Customer Create',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.create',
            ],
            [
                'name' => 'Customer Store',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.store',
            ],
            [
                'name' => 'Customer Active',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.active',
            ],
            [
                'name' => 'Customer Banned',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.banned',
            ],
            [
                'name' => 'Customer Detail',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.detail',
            ],
            [
                'name' => 'Customer Update',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.update',
            ],
            [
                'name' => 'Customer Statement',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.statement',
            ],
            [
                'name' => 'Customer Notification Single',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.notification.single',
            ],
            [
                'name' => 'Customer Show Notification All Form',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.showNotificationAllForm',
            ],
            [
                'name' => 'Customer Send Notification All',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.sendNotificationAll',
            ],
            [
                'name' => 'Customer List',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.list',
            ],
            [
                'name' => 'Customer Notification Log',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.notificationLog',
            ],
            [
                'name' => 'Customer List Print',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.customerlist',
            ],
            [
                'name' => 'Customer Product Comission List',
                'controller' => 'ManageCustomerController',
                'code' => 'admin.customer.customerproductcomissionlist',
            ],

            // accounts section here..........

            // PaymentMethodController
            [
                'name' => 'Payment Method Lists',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.list',
            ],
            [
                'name' => 'Payment Method Create',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.create',
            ],
            [
                'name' => 'Payment Method Store',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.store',
            ],
            [
                'name' => 'Payment Method Show',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.show',
            ],
            [
                'name' => 'Payment Method Edit',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.edit',
            ],
            [
                'name' => 'Payment Method Update',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.update',
            ],
            [
                'name' => 'Payment Method Delete',
                'controller' => 'PaymentMethodController',
                'code' => 'admin.paymentmethod.destroy',
            ],

            // AccountController

            [
                'name' => 'Account Lists',
                'controller' => 'AccountController',
                'code' => 'admin.account.list',
            ],
            [
                'name' => 'Account Create',
                'controller' => 'AccountController',
                'code' => 'admin.account.create',
            ],
            [
                'name' => 'Account Store',
                'controller' => 'AccountController',
                'code' => 'admin.account.store',
            ],
            [
                'name' => 'Account Show',
                'controller' => 'AccountController',
                'code' => 'admin.account.show',
            ],
            [
                'name' => 'Account Edit',
                'controller' => 'AccountController',
                'code' => 'admin.account.edit',
            ],
            [
                'name' => 'Account Update',
                'controller' => 'AccountController',
                'code' => 'admin.account.update',
            ],
            [
                'name' => 'Account Delete',
                'controller' => 'AccountController',
                'code' => 'admin.account.destroy',
            ],

            // AccountTransferController

            [
                'name' => 'Account Transfer Lists',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.list',
            ],
            [
                'name' => 'Account Transfer Create',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.create',
            ],
            [
                'name' => 'Account Transfer Store',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.store',
            ],
            [
                'name' => 'Account Transfer Show',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.show',
            ],
            [
                'name' => 'Account Transfer Edit',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.edit',
            ],
            [
                'name' => 'Account Transfer Update',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.update',
            ],
            [
                'name' => 'Account Transfer Delete',
                'controller' => 'AccountTransferController',
                'code' => 'admin.accounttransfer.destroy',
            ],

            // DepositController


            [
                'name' => 'Deposit Lists',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.list',
            ],
            [
                'name' => 'Deposit Create',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.create',
            ],
            [
                'name' => 'Deposit Store',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.store',
            ],
            [
                'name' => 'Deposit Show',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.show',
            ],
            [
                'name' => 'Deposit Edit',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.edit',
            ],
            [
                'name' => 'Deposit Update',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.update',
            ],
            [
                'name' => 'Deposit Delete',
                'controller' => 'DepositController',
                'code' => 'admin.deposit.destroy',
            ],

            // OfficialLoanPaymentController
            [
                'name' => 'Official Loan Payment Lists',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.list',
            ],
            [
                'name' => 'Official Loan Payment Create',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.create',
            ],
            [
                'name' => 'Official Loan Payment Store',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.store',
            ],
            [
                'name' => 'Official Loan Payment Show',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.show',
            ],
            [
                'name' => 'Official Loan Payment Edit',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.edit',
            ],
            [
                'name' => 'Official Loan Payment Update',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.update',
            ],
            [
                'name' => 'Official Loan Payment Delete',
                'controller' => 'OfficialLoanPaymentController',
                'code' => 'admin.officialloanpayment.destroy',
            ],

            // WithdrawalController
            [
                'name' => 'Withdrawal Lists',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.list',
            ],
            [
                'name' => 'Withdrawal Create',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.create',
            ],
            [
                'name' => 'Withdrawal Store',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.store',
            ],
            [
                'name' => 'Withdrawal Show',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.show',
            ],
            [
                'name' => 'Withdrawal Edit',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.edit',
            ],
            [
                'name' => 'Withdrawal Update',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.update',
            ],
            [
                'name' => 'Withdrawal Delete',
                'controller' => 'WithdrawalController',
                'code' => 'admin.withdrawal.destroy',
            ],

            // CustomerAdvanceController
            [
                'name' => 'Customer Advance Lists',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.list',
            ],
            [
                'name' => 'Customer Advance Create',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.create',
            ],
            [
                'name' => 'Customer Advance Store',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.store',
            ],
            [
                'name' => 'Customer Advance Show',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.show',
            ],
            [
                'name' => 'Customer Advance Edit',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.edit',
            ],
            [
                'name' => 'Customer Advance Update',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.update',
            ],
            [
                'name' => 'Customer Advance Delete',
                'controller' => 'CustomerAdvanceController',
                'code' => 'admin.customeradvance.destroy',
            ],

            // CustomerDuePaymentController

            [
                'name' => 'Customer Due Payment Lists',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.list',
            ],
            [
                'name' => 'Customer Due Payment Create',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.create',
            ],
            [
                'name' => 'Customer Due Payment Store',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.store',
            ],
            [
                'name' => 'Customer Due Payment Show',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.show',
            ],
            [
                'name' => 'Customer Due Payment Edit',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.edit',
            ],
            [
                'name' => 'Customer Due Payment Update',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.update',
            ],
            [
                'name' => 'Customer Due Payment Delete',
                'controller' => 'CustomerDuePaymentController',
                'code' => 'admin.customerduepayment.destroy',
            ],

            // SupplierDuePaymentController

            [
                'name' => 'Supplier Due Payment Lists',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.list',
            ],
            [
                'name' => 'Supplier Due Payment Create',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.create',
            ],
            [
                'name' => 'Supplier Due Payment Store',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.store',
            ],
            [
                'name' => 'Supplier Due Payment Show',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.show',
            ],
            [
                'name' => 'Supplier Due Payment Edit',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.edit',
            ],
            [
                'name' => 'Supplier Due Payment Update',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.update',
            ],
            [
                'name' => 'Supplier Due Payment Delete',
                'controller' => 'SupplierDuePaymentController',
                'code' => 'admin.supplierduepayment.destroy',
            ],

            // ModuleTypeController

            [
                'name' => 'Supplier Due Payment Lists',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.list',
            ],
            [
                'name' => 'Supplier Due Payment Create',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.create',
            ],
            [
                'name' => 'Supplier Due Payment Store',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.store',
            ],
            [
                'name' => 'Supplier Due Payment Show',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.show',
            ],
            [
                'name' => 'Supplier Due Payment Edit',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.edit',
            ],
            [
                'name' => 'Supplier Due Payment Update',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.update',
            ],
            [
                'name' => 'Supplier Due Payment Delete',
                'controller' => 'ModuleTypeController',
                'code' => 'admin.moduletype.destroy',
            ],

            // TransactionHistoryController

            [
                'name' => 'Transaction History Lists',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.list',
            ],
            [
                'name' => 'Transaction History Create',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.create',
            ],
            [
                'name' => 'Transaction History Store',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.store',
            ],
            [
                'name' => 'Transaction History Show',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.show',
            ],
            [
                'name' => 'Transaction HistoryEdit',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.edit',
            ],
            [
                'name' => 'Transaction History Update',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.update',
            ],
            [
                'name' => 'Transaction History Delete',
                'controller' => 'TransactionHistoryController',
                'code' => 'admin.transactionhistory.destroy',
            ],


            // DepartmentController

            [
                'name' => 'Department Lists',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.list',
            ],
            [
                'name' => 'Department Create',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.create',
            ],
            [
                'name' => 'Department Store',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.store',
            ],
            [
                'name' => 'Department Show',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.show',
            ],
            [
                'name' => 'Department Edit',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.edit',
            ],
            [
                'name' => 'Department Update',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.update',
            ],
            [
                'name' => 'Department Delete',
                'controller' => 'DepartmentController',
                'code' => 'admin.department.destroy',
            ],

            // EmployeeController

            [
                'name' => 'Employee Lists',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.list',
            ],
            [
                'name' => 'Employee Create',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.create',
            ],
            [
                'name' => 'Employee Store',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.store',
            ],
            [
                'name' => 'Employee Show',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.show',
            ],
            [
                'name' => 'Employee Edit',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.edit',
            ],
            [
                'name' => 'Employee Update',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.update',
            ],
            [
                'name' => 'Employee Delete',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.destroy',
            ],

            // EmployeeController

            [
                'name' => 'Employee Lists',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.list',
            ],
            [
                'name' => 'Employee Create',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.create',
            ],
            [
                'name' => 'Employee Store',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.store',
            ],
            [
                'name' => 'Employee Show',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.show',
            ],
            [
                'name' => 'Employee Edit',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.edit',
            ],
            [
                'name' => 'Employee Update',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.update',
            ],
            [
                'name' => 'Employee Delete',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.destroy',
            ],
            [
                'name' => 'Employee Status',
                'controller' => 'EmployeeController',
                'code' => 'admin.employee.status',
            ],

            // AttendanceController

            [
                'name' => 'Attendance Lists',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.list',
            ],
            [
                'name' => 'Attendance Create',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.create',
            ],
            [
                'name' => 'Attendance Store',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.store',
            ],
            [
                'name' => 'Attendance Show',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.show',
            ],
            [
                'name' => 'Attendance Edit',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.edit',
            ],
            [
                'name' => 'Attendance Update',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.update',
            ],
            [
                'name' => 'Attendance Delete',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.destroy',
            ],
            [
                'name' => 'Attendance Status',
                'controller' => 'AttendanceController',
                'code' => 'admin.attendance.status',
            ],

            // SalaryTypeController
            [
                'name' => 'Salary Type Lists',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.list',
            ],
            [
                'name' => 'Salary Type Create',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.create',
            ],
            [
                'name' => 'Salary Type Store',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.store',
            ],
            [
                'name' => 'Salary Type Show',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.show',
            ],
            [
                'name' => 'Salary Type Edit',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.edit',
            ],
            [
                'name' => 'Salary Type Update',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.update',
            ],
            [
                'name' => 'Salary Type Delete',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.destroy',
            ],
            [
                'name' => 'Salary Type Status',
                'controller' => 'SalaryTypeController',
                'code' => 'admin.salarytype.status',
            ],


            // SalaryAdvanceController
            [
                'name' => 'Salary Advance Lists',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.list',
            ],
            [
                'name' => 'Salary Advance Create',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.create',
            ],
            [
                'name' => 'Salary Advance Store',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.store',
            ],
            [
                'name' => 'Salary Advance Show',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.show',
            ],
            [
                'name' => 'Salary Advance Edit',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.edit',
            ],
            [
                'name' => 'Salary Advance Update',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.update',
            ],
            [
                'name' => 'Salary Advance Delete',
                'controller' => 'SalaryAdvanceController',
                'code' => 'admin.salaryadvance.destroy',
            ],


            // SalaryBonusSetupController
            [
                'name' => 'Salary bonus Setup Lists',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.list',
            ],
            [
                'name' => 'Salary bonus Setup Create',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.create',
            ],
            [
                'name' => 'Salary bonus Setup Store',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.store',
            ],
            [
                'name' => 'Salary bonus Setup Show',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.show',
            ],
            [
                'name' => 'Salary bonus Setup Edit',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.edit',
            ],
            [
                'name' => 'Salary bonus Setup Update',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.update',
            ],
            [
                'name' => 'Salary bonus Setup Delete',
                'controller' => 'SalaryBonusSetupController',
                'code' => 'admin.salarybonussetup.destroy',
            ],

            // LoanController
            [
                'name' => 'Loan Lists',
                'controller' => 'LoanController',
                'code' => 'admin.loan.list',
            ],
            [
                'name' => 'Loan Create',
                'controller' => 'LoanController',
                'code' => 'admin.loan.create',
            ],
            [
                'name' => 'Loan Store',
                'controller' => 'LoanController',
                'code' => 'admin.loan.store',
            ],
            [
                'name' => 'Loan Show',
                'controller' => 'LoanController',
                'code' => 'admin.loan.show',
            ],
            [
                'name' => 'Loan Edit',
                'controller' => 'LoanController',
                'code' => 'admin.loan.edit',
            ],
            [
                'name' => 'Loan Update',
                'controller' => 'LoanController',
                'code' => 'admin.loan.update',
            ],
            [
                'name' => 'Loan Delete',
                'controller' => 'LoanController',
                'code' => 'admin.loan.destroy',
            ],


            // SalaryDeductionController
            [
                'name' => 'Salary Deduction Lists',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.list',
            ],
            [
                'name' => 'Salary Deduction Create',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.create',
            ],
            [
                'name' => 'Salary Deduction Store',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.store',
            ],
            [
                'name' => 'Salary Deduction Show',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.show',
            ],
            [
                'name' => 'Salary Deduction Edit',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.edit',
            ],
            [
                'name' => 'Salary Deduction Update',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.update',
            ],
            [
                'name' => 'Salary Deduction Delete',
                'controller' => 'SalaryDeductionController',
                'code' => 'admin.salarydeduction.destroy',
            ],

            // OverTimeAllowanceController
            [
                'name' => 'Over Time Allowance Lists',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.list',
            ],
            [
                'name' => 'Over Time Allowance Create',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.create',
            ],
            [
                'name' => 'Over Time Allowance Store',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.store',
            ],
            [
                'name' => 'Over Time Allowance Show',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.show',
            ],
            [
                'name' => 'Over Time Allowance Edit',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.edit',
            ],
            [
                'name' => 'Over Time Allowance Update',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.update',
            ],
            [
                'name' => 'Over Time Allowance Delete',
                'controller' => 'OverTimeAllowanceController',
                'code' => 'admin.overtimeallowance.destroy',
            ],


            // SalaryGenerateController
            [
                'name' => 'Salary Generate Lists',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.list',
            ],
            [
                'name' => 'Salary Generate Create',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.create',
            ],
            [
                'name' => 'Salary Generate Department Wise Create',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.departmentwise.list',
            ],
            [
                'name' => 'Salary Generate Store',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.store',
            ],
            [
                'name' => 'Salary Generate Show',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.show',
            ],
            [
                'name' => 'Salary Generate Edit',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.edit',
            ],
            [
                'name' => 'Salary Generate Update',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.update',
            ],
            [
                'name' => 'Salary Generate Delete',
                'controller' => 'SalaryGenerateController',
                'code' => 'admin.salarygenerate.destroy',
            ],

            // SalaryPaymentHistoryController
            [
                'name' => 'Salary Payment History Lists',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.list',
            ],
            [
                'name' => 'Salary Payment History Create',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.create',
            ],
            [
                'name' => 'Salary Payment History Store',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.store',
            ],
            [
                'name' => 'Salary Payment History Show',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.show',
            ],
            [
                'name' => 'Salary Payment History Edit',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.edit',
            ],
            [
                'name' => 'Salary Payment History Update',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.update',
            ],
            [
                'name' => 'Salary Payment History Delete',
                'controller' => 'SalaryPaymentHistoryController',
                'code' => 'admin.salarypaymenthistory.destroy',
            ],

            // ProductCategoryController

            [
                'name' => 'Product Category Lists',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.list',
            ],
            [
                'name' => 'Product Category Create',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.create',
            ],
            [
                'name' => 'Product Category Store',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.store',
            ],
            [
                'name' => 'Product Category Show',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.show',
            ],
            [
                'name' => 'Product Category Edit',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.edit',
            ],
            [
                'name' => 'Product Category Update',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.update',
            ],
            [
                'name' => 'Product Category Delete',
                'controller' => 'ProductCategoryController',
                'code' => 'admin.productcategory.destroy',
            ],

            // ProductController
            [
                'name' => 'Product Lists',
                'controller' => 'ProductController',
                'code' => 'admin.product.list',
            ],
            [
                'name' => 'Product Create',
                'controller' => 'ProductController',
                'code' => 'admin.product.create',
            ],
            [
                'name' => 'Product Store',
                'controller' => 'ProductController',
                'code' => 'admin.product.store',
            ],
            [
                'name' => 'Product Show',
                'controller' => 'ProductController',
                'code' => 'admin.product.show',
            ],
            [
                'name' => 'Product Edit',
                'controller' => 'ProductController',
                'code' => 'admin.product.edit',
            ],
            [
                'name' => 'Product Update',
                'controller' => 'ProductController',
                'code' => 'admin.product.update',
            ],
            [
                'name' => 'Product Delete',
                'controller' => 'ProductController',
                'code' => 'admin.product.destroy',
            ],
            [
                'name' => 'Product Status',
                'controller' => 'ProductController',
                'code' => 'admin.product.status',
            ],
            [
                'name' => 'Product Customer Price',
                'controller' => 'ProductController',
                'code' => 'admin.product.customerprice',
            ],
            [
                'name' => 'Product Customer Price Update',
                'controller' => 'ProductController',
                'code' => 'admin.product.customerpriceupdate',
            ],


            // ProductStockController

            [
                'name' => 'Product Stock Lists',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.list',
            ],
            [
                'name' => 'Product Stock Create',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.create',
            ],
            [
                'name' => 'Product Stock Store',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.store',
            ],
            [
                'name' => 'Product Stock Show',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.show',
            ],
            [
                'name' => 'Product Stock Edit',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.edit',
            ],
            [
                'name' => 'Product Stock Update',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.update',
            ],
            [
                'name' => 'Product Stock Delete',
                'controller' => 'ProductStockController',
                'code' => 'admin.productstock.destroy',
            ],

            // ProductDamageController
            [
                'name' => 'Product Damage Lists',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.list',
            ],
            [
                'name' => 'Product Damage Create',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.create',
            ],
            [
                'name' => 'Product Damage Store',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.store',
            ],
            [
                'name' => 'Product Damage Show',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.show',
            ],
            [
                'name' => 'Product Damage Edit',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.edit',
            ],
            [
                'name' => 'Product Damage Update',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.update',
            ],
            [
                'name' => 'Product Damage Delete',
                'controller' => 'ProductDamageController',
                'code' => 'admin.productdamage.destroy',
            ],

            // CustomerProductDamageController

            [
                'name' => 'Customer Product Damage Lists',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.list',
            ],
            [
                'name' => 'Customer Product Damage Create',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.create',
            ],
            [
                'name' => 'Customer Product Damage Store',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.store',
            ],
            [
                'name' => 'Customer Product Damage Show',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.show',
            ],
            [
                'name' => 'Customer Product Damage Edit',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.edit',
            ],
            [
                'name' => 'Customer Product Damage Update',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.update',
            ],
            [
                'name' => 'Customer Product Damage Delete',
                'controller' => 'CustomerProductDamageController',
                'code' => 'admin.customerproductdamage.destroy',
            ],

            // Expense Module
            [
                'name' => 'Expense',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.module',
            ],

            // ExpenseCategoryController
            [
                'name' => 'Expense Category Lists',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.list',
            ],
            [
                'name' => 'Expense Category Create',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.create',
            ],
            [
                'name' => 'Expense Category Store',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.store',
            ],
            [
                'name' => 'Expense Category Show',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.show',
            ],
            [
                'name' => 'Expense Category Edit',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.edit',
            ],
            [
                'name' => 'Expense Category Update',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.update',
            ],
            [
                'name' => 'Expense Category Delete',
                'controller' => 'ExpenseCategoryController',
                'code' => 'admin.expensecategory.destroy',
            ],

            // ExpenseController
            [
                'name' => 'Expense Lists',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.list',
            ],
            [
                'name' => 'Expense Create',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.create',
            ],
            [
                'name' => 'Expense Store',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.store',
            ],
            [
                'name' => 'Expense Show',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.show',
            ],
            [
                'name' => 'Expense Edit',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.edit',
            ],
            [
                'name' => 'Expense Update',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.update',
            ],
            [
                'name' => 'Expense Delete',
                'controller' => 'ExpenseController',
                'code' => 'admin.expense.destroy',
            ],

            // ExpenseDetailController
            [
                'name' => 'Expense Detail Lists',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.list',
            ],
            [
                'name' => 'Expense Detail Create',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.create',
            ],
            [
                'name' => 'Expense Detail Store',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.store',
            ],
            [
                'name' => 'Expense Detail Show',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.show',
            ],
            [
                'name' => 'Expense Detail Edit',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.edit',
            ],
            [
                'name' => 'Expense Detail Update',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.update',
            ],
            [
                'name' => 'Expense Detail Delete',
                'controller' => 'ExpenseDetailController',
                'code' => 'admin.expensedetail.destroy',
            ],

            // ExpensePaymentHistoryController
            [
                'name' => 'Expense Payment History Lists',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.list',
            ],
            [
                'name' => 'Expense Payment History Create',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.create',
            ],
            [
                'name' => 'Expense Payment History Store',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.store',
            ],
            [
                'name' => 'Expense Payment History Show',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.show',
            ],
            [
                'name' => 'Expense Payment History Edit',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.edit',
            ],
            [
                'name' => 'Expense Payment History Update',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.update',
            ],
            [
                'name' => 'Expense Payment History Delete',
                'controller' => 'ExpensePaymentHistoryController',
                'code' => 'admin.expensepaymenthistory.destroy',
            ],

            // DailyProductionController

            [
                'name' => 'Daily Production Lists',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.list',
            ],
            [
                'name' => 'Daily Productiony Create',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.create',
            ],
            [
                'name' => 'Daily Production Store',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.store',
            ],
            [
                'name' => 'Daily Production Show',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.show',
            ],
            [
                'name' => 'Daily Production Edit',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.edit',
            ],
            [
                'name' => 'Daily Production Update',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.update',
            ],
            [
                'name' => 'Daily Production Delete',
                'controller' => 'DailyProductionController',
                'code' => 'admin.dailyproduction.destroy',
            ],

            // ProductionReportController

            [
                'name' => 'Production Report',
                'controller' => 'ProductionReportController',
                'code' => 'admin.dailyproduction.productionreport',
            ],
            [
                'name' => 'Production Group Report',
                'controller' => 'ProductionReportController',
                'code' => 'admin.dailyproduction.productionGroupReport',
            ],
            [
                'name' => 'Production Report Exports',
                'controller' => 'ProductionReportController',
                'code' => 'admin.dailyproduction.productionreport.exports',
            ],
            [
                'name' => 'Production Group Report Exports',
                'controller' => 'ProductionReportController',
                'code' => 'admin.dailyproduction.productionGroupReport.exports',
            ],


            // MakeProductionController
            [
                'name' => 'Make Production Lists',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.list',
            ],
            [
                'name' => 'Make Productiony Create',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.create',
            ],
            [
                'name' => 'Make Production Store',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.store',
            ],
            [
                'name' => 'Make Production Show',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.show',
            ],
            [
                'name' => 'Make Production Edit',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.edit',
            ],
            [
                'name' => 'Make Production Update',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.update',
            ],
            [
                'name' => 'Make Production Delete',
                'controller' => 'MakeProductionController',
                'code' => 'admin.makeproduction.destroy',
            ],


            // ProductionLossController

            [
                'name' => 'Production Loss Lists',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.list',
            ],
            [
                'name' => 'Production Loss Create',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.create',
            ],
            [
                'name' => 'Production Loss Store',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.store',
            ],
            [
                'name' => 'Production Loss Show',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.show',
            ],
            [
                'name' => 'Production Loss Edit',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.edit',
            ],
            [
                'name' => 'Production Loss Update',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.update',
            ],
            [
                'name' => 'Production Loss Delete',
                'controller' => 'ProductionLossController',
                'code' => 'admin.productionloss.destroy',
            ],

            // ReportController

            [
                'name' => 'Profit Loss',
                'controller' => 'ReportController',
                'code' => 'admin.profitloss.list',
            ],
            [
                'name' => 'Daily Reports',
                'controller' => 'ReportController',
                'code' => 'admin.dailyreports.list',
            ],
            [
                'name' => 'Purchase sales',
                'controller' => 'ReportController',
                'code' => 'admin.purchasesell.list',
            ],
            [
                'name' => 'Customer Supplier',
                'controller' => 'ReportController',
                'code' => 'admin.customersupplier.list',
            ],
            [
                'name' => 'Stock Report',
                'controller' => 'ReportController',
                'code' => 'admin.stockreport.list',
            ],
            [
                'name' => 'Item Stock Report',
                'controller' => 'ReportController',
                'code' => 'admin.itemstockreport.list',
            ],
            [
                'name' => 'Stock Adjustment Report',
                'controller' => 'ReportController',
                'code' => 'admin.stockadjustment.list',
            ],
            [
                'name' => 'Product Purchase Report',
                'controller' => 'ReportController',
                'code' => 'admin.productpurchasereport.list',
            ],
            [
                'name' => 'Product Sales Report',
                'controller' => 'ReportController',
                'code' => 'admin.productsalesreport.list',
            ],
            [
                'name' => 'Register Report',
                'controller' => 'ReportController',
                'code' => 'admin.registerreport.list',
            ],

            // AssetController
            [
                'name' => 'Asset Lists',
                'controller' => 'AssetController',
                'code' => 'admin.asset.list',
            ],
            [
                'name' => 'Asset Create',
                'controller' => 'AssetController',
                'code' => 'admin.asset.create',
            ],
            [
                'name' => 'Asset Store',
                'controller' => 'AssetController',
                'code' => 'admin.asset.store',
            ],
            [
                'name' => 'Asset Show',
                'controller' => 'AssetController',
                'code' => 'admin.asset.show',
            ],
            [
                'name' => 'Asset Edit',
                'controller' => 'AssetController',
                'code' => 'admin.asset.edit',
            ],
            [
                'name' => 'Asset Update',
                'controller' => 'AssetController',
                'code' => 'admin.asset.update',
            ],
            [
                'name' => 'Asset Delete',
                'controller' => 'AssetController',
                'code' => 'admin.asset.destroy',
            ],

            // StaffController

            [
                'name' => 'Manage User',
                'controller' => 'StaffController',
                'code' => 'admin.manage.user',
            ],
            [
                'name' => 'Staff Lists',
                'controller' => 'StaffController',
                'code' => 'admin.staff.list',
            ],
            [
                'name' => 'Staff Create',
                'controller' => 'StaffController',
                'code' => 'admin.staff.create',
            ],
            [
                'name' => 'Staff Store',
                'controller' => 'StaffController',
                'code' => 'admin.staff.store',
            ],
            [
                'name' => 'Staff Show',
                'controller' => 'StaffController',
                'code' => 'admin.staff.show',
            ],
            [
                'name' => 'Staff Edit',
                'controller' => 'StaffController',
                'code' => 'admin.staff.edit',
            ],
            [
                'name' => 'Staff Update',
                'controller' => 'StaffController',
                'code' => 'admin.staff.update',
            ],
            [
                'name' => 'Staff Delete',
                'controller' => 'StaffController',
                'code' => 'admin.staff.destroy',
            ],
            [
                'name' => 'Staff Status',
                'controller' => 'StaffController',
                'code' => 'admin.staff.status',
            ],
            [
                'name' => 'Staff Login',
                'controller' => 'StaffController',
                'code' => 'admin.staff.login',
            ],


            // FeatureController

            [
                'name' => 'Access Setting',
                'controller' => 'FeatureController',
                'code' => 'admin.setting.access',
            ],
            [
                'name' => 'Access Product',
                'controller' => 'FeatureController',
                'code' => 'admin.setting.access.product',
            ],
            [
                'name' => 'Access Quotation',
                'controller' => 'FeatureController',
                'code' => 'admin.quotation.access',
            ],
            [
                'name' => 'Access Order',
                'controller' => 'FeatureController',
                'code' => 'admin.order.access',
            ],
            [
                'name' => 'Access Item',
                'controller' => 'FeatureController',
                'code' => 'admin.item.access',
            ],
            [
                'name' => 'Access Item Order',
                'controller' => 'FeatureController',
                'code' => 'admin.itemorder.access',
            ],
            [
                'name' => 'Access Customer & Supplier',
                'controller' => 'FeatureController',
                'code' => 'admin.setting.access.customer.supplier',
            ],
            [
                'name' => 'Access Account',
                'controller' => 'FeatureController',
                'code' => 'admin.setting.access.account',
            ],
            [
                'name' => 'Access Payroll',
                'controller' => 'FeatureController',
                'code' => 'admin.setting.access.payroll',
            ],

            [
                'name' => 'Access Reports',
                'controller' => 'FeatureController',
                'code' => 'admin.reports',
            ],


            // SettingController
            [
                'name' => 'General Setting',
                'controller' => 'SettingController',
                'code' => 'admin.setting.general',
            ],
            [
                'name' => 'General Setting Edit',
                'controller' => 'SettingController',
                'code' => 'admin.setting.general.edit',
            ],
            [
                'name' => 'Logo & Icon Setting',
                'controller' => 'SettingController',
                'code' => 'admin.setting.logo',
            ],
            [
                'name' => 'Language Setting',
                'controller' => 'SettingController',
                'code' => 'admin.language.manage',
            ],
            [
                'name' => 'Add New Language',
                'controller' => 'SettingController',
                'code' => 'admin.language.add_new',
            ],
            [
                'name' => 'language Delete',
                'controller' => 'SettingController',
                'code' => 'admin.language.delete',
            ],
            [
                'name' => 'language Edit',
                'controller' => 'SettingController',
                'code' => 'admin.language.edit',
            ],
            [
                'name' => 'language Import',
                'controller' => 'SettingController',
                'code' => 'admin.language.import',
            ],
            [
                'name' => 'language Store JSON',
                'controller' => 'SettingController',
                'code' => 'admin.language.store.json',
            ],
            [
                'name' => 'language Delete JSON',
                'controller' => 'SettingController',
                'code' => 'admin.language.delete.json',
            ],
            [
                'name' => 'language Update JSON',
                'controller' => 'SettingController',
                'code' => 'admin.language.update.json',
            ],

            // AssetExpenseController
            [
                'name' => 'Asset Expense Lists',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.list',
            ],
            [
                'name' => 'Asset Expense Create',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.create',
            ],
            [
                'name' => 'Asset Expense Store',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.store',
            ],
            [
                'name' => 'Asset Expense Show',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.show',
            ],
            [
                'name' => 'Asset Expense Edit',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.edit',
            ],
            [
                'name' => 'Asset Expense Update',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.update',
            ],
            [
                'name' => 'Asset Expense Delete',
                'controller' => 'AssetExpenseController',
                'code' => 'admin.assetexpenses.destroy',
            ],

            // MonthlyExpenseController
            [
                'name' => 'Monthly Expense Lists',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.list',
            ],
            [
                'name' => 'Monthly Expense Create',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.create',
            ],
            [
                'name' => 'Monthly Expense Store',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.store',
            ],
            [
                'name' => 'Monthly Expense Show',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.show',
            ],
            [
                'name' => 'Monthly Expense Edit',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.edit',
            ],
            [
                'name' => 'Monthly Expense Update',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.update',
            ],
            [
                'name' => 'Monthly Expense Delete',
                'controller' => 'MonthlyExpenseController',
                'code' => 'admin.monthlyexpenses.destroy',
            ],

            // TransportExpenseController
            [
                'name' => 'Transport Expense Lists',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.list',
            ],
            [
                'name' => 'Transport Expense Create',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.create',
            ],
            [
                'name' => 'Transport Expense Store',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.store',
            ],
            [
                'name' => 'Transport Expense Show',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.show',
            ],
            [
                'name' => 'Transport Expense Edit',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.edit',
            ],
            [
                'name' => 'Transport Expense Update',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.update',
            ],
            [
                'name' => 'Transport Expense Delete',
                'controller' => 'TransportExpenseController',
                'code' => 'admin.transportexpenses.destroy',
            ],

        ];


        foreach ($permissionGroups as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'group' => $this->removeControllerSuffix($permission['controller']),
                'code' => $permission['code'],
            ]);
        }


        $role = Role::find(1);

        if ($role) {
            $permissions = Permission::all();
            $role->permission()->sync($permissions->pluck('id')->toArray());
        }
    }

    /**
     * Remove "Controller" suffix from the controller name.
     */
    protected function removeControllerSuffix(string $string): string
    {
        return str_replace('Controller', '', $string);
    }
}

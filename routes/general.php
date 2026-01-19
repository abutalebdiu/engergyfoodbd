<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;

use App\Http\Controllers\Admin\Auth\RolesController;
use App\Http\Controllers\Admin\Auth\StaffController;
use App\Http\Controllers\Admin\Setting\SupportController;
use App\Http\Controllers\Admin\Setting\FrontendController;
use App\Http\Controllers\Admin\Setting\LanguageController;
use App\Http\Controllers\Admin\Setting\ExtensionController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;


use App\Http\Controllers\Admin\Setting\PermissionController;
use App\Http\Controllers\Admin\Setting\SubscriberController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Setting\PageBuilderController;
use App\Http\Controllers\Admin\Order\ManageCustomerController;
use App\Http\Controllers\Admin\Order\ManageSupplierController;
use App\Http\Controllers\Admin\Setting\NotificationController;
use App\Http\Controllers\Admin\Setting\GeneralSettingController;
use App\Http\Controllers\Admin\Commission\ReferenceCommisionController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'Login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

//     Admin Password Reset
Route::prefix('password')->name('password.')->group(function () {
    Route::get('reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('reset');
    Route::post('reset', [ForgotPasswordController::class, 'sendResetCodeEmail']);
    Route::get('code-verify', [ForgotPasswordController::class, 'codeVerify'])->name('code.verify');
    Route::post('verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('verify.code');
});

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset/change', [ResetPasswordController::class, 'reset'])->name('password.change');

Route::group(['middleware' => ['auth:web,admin']], function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
    Route::get('password', [AdminController::class, 'password'])->name('password');
    Route::post('password', [AdminController::class, 'passwordUpdate'])->name('password.update');

    Route::get('orderreset', [AdminController::class, 'orderreset'])->name('order.reset');
    Route::get('accountreset', [AdminController::class, 'accountreset'])->name('account.reset');


    //Notification
    Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('notification/read/{id}', [AdminController::class, 'notificationRead'])->name('notification.read');
    Route::get('notifications/read-all', [AdminController::class, 'readAll'])->name('notifications.readAll');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
    Route::get('password', [AdminController::class, 'password'])->name('password');
    Route::post('password', [AdminController::class, 'passwordUpdate'])->name('password.update');

    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('staff/save/{id?}', [StaffController::class, 'save'])->name('staff.save');
    Route::post('staff/switch-status/{id}', [StaffController::class, 'status'])->name('staff.status');
    Route::get('staff/login/{id}', [StaffController::class, 'login'])->name('staff.login');

    // role and permission

    Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
        Route::get('/index', [RolesController::class, 'index'])->name('index');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('/edit/{id?}', [RolesController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RolesController::class, 'update'])->name('update');
    });

    Route::get('permission', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permission/update-permissions', [PermissionController::class, 'updatePermissions'])->name('permissions.update');

    // Users Manager


    Route::name('suppliers.')->prefix('supplier')->group(function () {
        Route::get('/', [ManageSupplierController::class, 'allUsers'])->name('all');
        Route::get('create', [ManageSupplierController::class, 'create'])->name('create');
        Route::post('store', [ManageSupplierController::class, 'store'])->name('store');
        Route::get('active', [ManageSupplierController::class, 'activeUsers'])->name('active');
        Route::get('banned', [ManageSupplierController::class, 'bannedUsers'])->name('banned');
        Route::get('detail/{id}', [ManageSupplierController::class, 'detail'])->name('detail');
        Route::post('update/{id}', [ManageSupplierController::class, 'update'])->name('update');
        Route::get('statement/{id}', [ManageSupplierController::class, 'statement'])->name('statement');
        Route::get('send-notification/{id}', [ManageSupplierController::class, 'showNotificationSingleForm'])->name('notification.single');
        Route::post('send-notification/{id}', [ManageSupplierController::class, 'sendNotificationSingle'])->name('notification.single');
        Route::get('login/{id}', [ManageSupplierController::class, 'login'])->name('login');
        Route::post('status/{id}', [ManageSupplierController::class, 'status'])->name('status');

        Route::get('send-notification', [ManageSupplierController::class, 'showNotificationAllForm'])->name('notification.all');
        Route::post('send-notification', [ManageSupplierController::class, 'sendNotificationAll'])->name('notification.all.send');
        Route::get('list', [ManageSupplierController::class, 'list'])->name('list');
        Route::get('notification-log/{id}', [ManageSupplierController::class, 'notificationLog'])->name('notification.log');
    });

    Route::name('customers.')->prefix('customer')->group(function () {
        Route::get('/', [ManageCustomerController::class, 'allUsers'])->name('all');
        Route::get('create', [ManageCustomerController::class, 'create'])->name('create');
        Route::post('store', [ManageCustomerController::class, 'store'])->name('store');
        Route::get('active', [ManageCustomerController::class, 'activeUsers'])->name('active');
        Route::get('banned', [ManageCustomerController::class, 'bannedUsers'])->name('banned');
        Route::get('statement/{id}', [ManageCustomerController::class, 'statement'])->name('statement');
        Route::get('detail/{id}', [ManageCustomerController::class, 'detail'])->name('detail');
        Route::post('update/{id}', [ManageCustomerController::class, 'update'])->name('update');
        Route::get('send-notification/{id}', [ManageCustomerController::class, 'showNotificationSingleForm'])->name('notification.single');
        Route::post('send-notification/{id}', [ManageCustomerController::class, 'sendNotificationSingle'])->name('notification.single');
        Route::get('login/{id}', [ManageCustomerController::class, 'login'])->name('login');
        Route::get('status/{id}', [ManageCustomerController::class, 'status'])->name('status');
        Route::get('delete/{id}', [ManageCustomerController::class, 'delete'])->name('delete');


        Route::get('list/print/pdf', [ManageCustomerController::class, 'customerlist'])->name('customerlist');
        Route::get('products/commission/{id}', [ManageCustomerController::class, 'customerproductcomissionlist'])->name('productcomissionlist');

        Route::get('send-notification', [ManageCustomerController::class, 'showNotificationAllForm'])->name('notification.all');
        Route::post('send-notification', [ManageCustomerController::class, 'sendNotificationAll'])->name('notification.all.send');
        Route::get('list', [ManageCustomerController::class, 'list'])->name('list');
        Route::get('notification-log/{id}', [ManageCustomerController::class, 'notificationLog'])->name('notification.log');
    });

    // Admin Support
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'tickets'])->name('index');
        Route::get('create', [SupportController::class, 'create'])->name('create');
        Route::get('pending', [SupportController::class, 'pendingSupport'])->name('pending');
        Route::get('closed', [SupportController::class, 'closedSupport'])->name('closed');
        Route::get('answered', [SupportController::class, 'answeredSupport'])->name('answered');
        Route::get('view/{id}', [SupportController::class, 'supportReply'])->name('view');
        Route::post('reply/{id}', [SupportController::class, 'replySupport'])->name('reply');
        Route::post('close/{id}', [SupportController::class, 'closedSupport'])->name('close');
        Route::get('download/{ticket}', [SupportController::class, 'ticketDownload'])->name('download');
        Route::post('delete/{id}', [SupportController::class, 'ticketDelete'])->name('delete');
    });



    // Subscriber
    Route::prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', [SubscriberController::class, 'index'])->name('index');
        Route::get('send-email', [SubscriberController::class, 'sendEmailForm'])->name('send.email');
        Route::post('remove/{id}', [SubscriberController::class, 'remove'])->name('remove');
        Route::post('send-email', [SubscriberController::class, 'sendEmail'])->name('send.email');
    });

    // General Setting
    Route::get('general-setting', [GeneralSettingController::class, 'index'])->name('setting.index');
    Route::post('general-setting', [GeneralSettingController::class, 'update'])->name('setting.update');

    //configuration
    Route::get('setting/system-configuration', [GeneralSettingController::class, 'systemConfiguration'])->name('setting.system.configuration');
    Route::post('setting/system-configuration', [GeneralSettingController::class, 'systemConfigurationSubmit']);

    // Logo-Icon
    Route::get('setting/logo-icon', [GeneralSettingController::class, 'logoIcon'])->name('setting.logo.icon');
    Route::post('setting/logo-icon', [GeneralSettingController::class, 'logoIconUpdate'])->name('setting.logo.icon');

    //Custom CSS
    Route::get('custom-css', [GeneralSettingController::class, 'customCss'])->name('setting.custom.css');
    Route::post('custom-css', [GeneralSettingController::class, 'customCssSubmit']);

    //Cookie
    Route::get('cookie', [GeneralSettingController::class, 'cookie'])->name('setting.cookie');
    Route::post('cookie', [GeneralSettingController::class, 'cookieSubmit']);

    //maintenance_mode
    Route::get('maintenance-mode', [GeneralSettingController::class, 'maintenanceMode'])->name('maintenance.mode');
    Route::post('maintenance-mode', [GeneralSettingController::class, 'maintenanceModeSubmit']);

    // Plugin
    Route::prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', [ExtensionController::class, 'index'])->name('index');
        Route::post('update/{id}', [ExtensionController::class, 'update'])->name('update');
        Route::post('status/{id}', [ExtensionController::class, 'status'])->name('status');
    });

    // Language Manager
    Route::prefix('language')->name('language.')->group(function () {
        Route::get('/', [LanguageController::class, 'langManage'])->name('manage');
        Route::post('/', [LanguageController::class, 'langStore'])->name('manage.store');
        Route::post('delete/{id}', [LanguageController::class, 'langDelete'])->name('manage.delete');
        Route::post('update/{id}', [LanguageController::class, 'langUpdate'])->name('manage.update');
        Route::get('edit/{id}', [LanguageController::class, 'langEdit'])->name('key');
        Route::post('import', [LanguageController::class, 'langImport'])->name('import.lang');
        Route::post('store/key/{id}', [LanguageController::class, 'storeLanguageJson'])->name('store.key');
        Route::post('delete/key/{id}', [LanguageController::class, 'deleteLanguageJson'])->name('delete.key');
        Route::post('update/key/{id}', [LanguageController::class, 'updateLanguageJson'])->name('update.key');
        Route::get('get-keys', [LanguageController::class, 'getKeys'])->name('get.key');
    });

    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {
        Route::get('frontend-sections/{key}', [FrontendController::class, 'frontendSections'])->name('sections');
        Route::post('frontend-content/{key}', [FrontendController::class, 'frontendContent'])->name('sections.content');
        Route::get('frontend-element/{key}/{id?}', [FrontendController::class, 'frontendElement'])->name('sections.element');
        Route::post('remove/{id}', [FrontendController::class, 'remove'])->name('remove');

        // Page Builder
        Route::get('manage-pages', [PageBuilderController::class, 'managePages'])->name('manage.pages');
        Route::post('manage-pages', [PageBuilderController::class, 'managePagesSave'])->name('manage.pages.save');
        Route::post('manage-pages/update', [PageBuilderController::class, 'managePagesUpdate'])->name('manage.pages.update');
        Route::post('manage-pages/delete/{id}', [PageBuilderController::class, 'managePagesDelete'])->name('manage.pages.delete');
        Route::get('manage-section/{id}', [PageBuilderController::class, 'manageSection'])->name('manage.section');
        Route::post('manage-section/{id}', [PageBuilderController::class, 'manageSectionUpdate'])->name('manage.section.update');
    });

    Route::get('seo', [FrontendController::class, 'seoEdit'])->name('seo');

    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::get('templates', [FrontendController::class, 'templates'])->name('templates');
        Route::post('templates', [FrontendController::class, 'templatesActive'])->name('templates.active');
        Route::get('frontend-sections/{key}', [FrontendController::class, 'frontendSections'])->name('sections');
        Route::post('frontend-content/{key}', [FrontendController::class, 'frontendContent'])->name('sections.content');
        Route::get('frontend-element/{key}/{id?}', [FrontendController::class, 'frontendElement'])->name('sections.element');
        Route::post('remove/{id}', [FrontendController::class, 'remove'])->name('remove');

        // Page Builder
        Route::get('manage-pages', [PageBuilderController::class, 'managePages'])->name('manage.pages');
        Route::post('manage-pages', [PageBuilderController::class, 'managePagesSave'])->name('manage.pages.save');
        Route::post('manage-pages/update', [PageBuilderController::class, 'managePagesUpdate'])->name('manage.pages.update');
        Route::post('manage-pages/delete/{id}', [PageBuilderController::class, 'managePagesDelete'])->name('manage.pages.delete');
        Route::get('manage-section/{id}', [PageBuilderController::class, 'manageSection'])->name('manage.section');
        Route::post('manage-section/{id}', [PageBuilderController::class, 'manageSectionUpdate'])->name('manage.section.update');
    });

    //Notification Setting
    Route::name('setting.notification.')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global', [NotificationController::class, 'global'])->name('global');
        Route::post('global/update', [NotificationController::class, 'globalUpdate'])->name('global.update');
        Route::get('templates', [NotificationController::class, 'templates'])->name('templates');
        Route::get('template/edit/{id}', [NotificationController::class, 'templateEdit'])->name('template.edit');
        Route::post('template/update/{id}', [NotificationController::class, 'templateUpdate'])->name('template.update');

        //Email Setting
        Route::get('email/setting', [NotificationController::class, 'emailSetting'])->name('email');
        Route::post('email/setting', [NotificationController::class, 'emailSettingUpdate']);
        Route::post('email/test', [NotificationController::class, 'emailTest'])->name('email.test');

        //SMS Setting
        Route::get('sms/setting', [NotificationController::class, 'smsSetting'])->name('sms');
        Route::post('sms/setting', [NotificationController::class, 'smsSettingUpdate']);
        Route::post('sms/test', [NotificationController::class, 'smsTest'])->name('sms.test');
    });


    Route::get('commission/{id}', [ReferenceCommisionController::class, 'referenceCommision'])->name('referenceCommision');
    Route::get('commission/pdf/{id}', [ReferenceCommisionController::class, 'referenceCommisionpdf'])->name('referenceCommisionpdf');
    Route::post('commission-update/{id}', [ReferenceCommisionController::class, 'referenceCommisionUpdate'])->name('referenceCommision.update');
});

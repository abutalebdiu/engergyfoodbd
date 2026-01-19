<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mail\SMSController;
use App\Http\Controllers\Mail\SettingController;
use App\Http\Controllers\Mail\SendMailController;
use App\Http\Controllers\Mail\SmsConfigController;
use App\Http\Controllers\Mail\DomainConfigController;





Route::group(['prefix' => 'send-mail', 'as' => 'send-mail.', 'controller' => SendMailController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/group/sendmail', 'indexsendmail')->name('group');
    Route::get('send-email/{id}', 'sendEmail')->name('send.email');
    Route::get('send-sms/{id}', 'sendSMS')->name('send.sms');
    Route::get('history', 'mailhistory')->name('mail.history');
});





Route::get('general-messaging/sendmessage', [SMSController::class, 'general'])->name('general-messaging.sendmessage');
Route::get('supplier/messaging/sendmessage', [SMSController::class, 'suppliersms'])->name('supplier.messaging.sendmessage');
Route::get('sms/history', [SMSController::class, 'smshistory'])->name('sms.history');

// store message
Route::post('send-general-message', [SMSController::class, 'sendGeneralMessage'])->name('sms.send.general.message');
Route::post('send-supplier-message', [SMSController::class, 'sendSupplierMessage'])->name('sms.send.supplier.message');


Route::post('send-mail', [SendMailController::class, 'store'])->name('mail.sendmail');
Route::post('send-general-mail', [SendMailController::class, 'sendGeneralMail'])->name('mail.send.general.mail');
Route::post('send-group-mail', [SendMailController::class, 'sendGroupMail'])->name('mail.send.group.mail');


Route::resource('domainconfig', DomainConfigController::class);
Route::resource('smsconfig', SmsConfigController::class);


Route::prefix('setting')->name('setting.')->group(function () {
    Route::get('/sms', [SettingController::class, 'sms'])->name('sms');
    Route::get('edit-sms/{id}', [SettingController::class, 'editSms'])->name('edit.sms');
    Route::post('sms-update/{id}', [SettingController::class, 'smsUpdate'])->name('store.sms');
    Route::get('/email', [SettingController::class, 'email'])->name('email');
    Route::get('/create-email', [SettingController::class, 'createEmail'])->name('createEmail');
    Route::post('/store-email', [SettingController::class, 'storeEmail'])->name('storeEmail');
    Route::get('edit-email/{id}', [SettingController::class, 'editEmail'])->name('edit.email');
    Route::post('email-update/{id}', [SettingController::class, 'emailUpdate'])->name('store.email');
});

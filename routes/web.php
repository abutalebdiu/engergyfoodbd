<?php

use App\Http\Controllers\SupportController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return back();
});

// User Support
Route::controller(SupportController::class)->prefix('support')->name('support.')->group(function () {
    Route::get('/all', 'supportTicket')->name('index');
    Route::get('new', 'openSupport')->name('open');
    Route::post('create', 'storeSupport')->name('store');
    Route::get('view/{number}', 'viewSupport')->name('view');
    Route::post('reply/{number}', 'replySupport')->name('reply');
    Route::post('close/{number}', 'closeSupport')->name('close');
    Route::get('download/{number}', 'supportDownload')->name('download');
});


Route::controller(WebController::class)->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');

    //web
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::post('subscribe', 'subscribe')->name('subscribe');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

require __DIR__ . '/auth.php';

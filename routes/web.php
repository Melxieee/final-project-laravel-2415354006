<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return redirect()->route('web.customers.index');
});

Route::name('web.')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::patch('customers/{customer}/activate', [CustomerController::class, 'activate'])->name('customers.activate');
    Route::patch('customers/{customer}/deactivate', [CustomerController::class, 'deactivate'])->name('customers.deactivate');

    Route::resource('services', ServiceController::class);
    Route::patch('services/{service}/activate', [ServiceController::class, 'activate'])->name('services.activate');
    Route::patch('services/{service}/deactivate', [ServiceController::class, 'deactivate'])->name('services.deactivate');

    Route::resource('subscriptions', SubscriptionController::class);
});

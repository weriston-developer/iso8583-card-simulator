<?php

use App\Infra\Presentation\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return 'CARD ISO8583 API';
});


Route::group([], function () {
    Route::post('/purchase', [TransactionController::class, 'purchase'])->name('purchase') ;
});

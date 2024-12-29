<?php

use App\Http\Controllers\Api\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/invoices', [InvoiceController::class, 'create']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
});

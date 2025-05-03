<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('product')->group(function () {
    Route::get('/all', [ProductController::class, 'getAll']);

    Route::get('/detail/{id}', [ProductController::class, 'getDetail']);

    Route::post('/create', [ProductController::class, 'create']);

    Route::post('/update/{id}', [ProductController::class, 'update']);

    Route::post('/stock/add/manual/{id}', [ProductController::class, 'addStockManual']);
    Route::get('/stock/get/{id}', [ProductController::class, 'getStock']);
});

Route::prefix('customer')->group(function () {
    Route::get('/all', [CustomerController::class, 'getAll']);

    Route::get('/detail/{id}', [CustomerController::class, 'getDetail']);

    Route::post('/create', [CustomerController::class, 'create']);

    Route::post('/update/{id}', [CustomerController::class, 'update']);
});

Route::prefix('vendor')->group(function () {
    Route::get('/all', [VendorController::class, 'getAll']);

    Route::get('/detail/{id}', [VendorController::class, 'getDetail']);

    Route::post('/create', [VendorController::class, 'create']);

    Route::post('/update/{id}', [VendorController::class, 'update']);
});

Route::prefix('purchase')->group(function () {
    Route::get('/all', [PurchaseController::class, 'getAll']);

    Route::get('/detail/{id}', [PurchaseController::class, 'getDetail']);

    Route::post('/create', [PurchaseController::class, 'create']);

    Route::post('/update/{id}', [PurchaseController::class, 'update']);
    Route::post('/add-payment/{id}', [PurchaseController::class, 'addPayment']);
    Route::post('/product-arrived/{id}', [PurchaseController::class, 'productArrived']);

    Route::post('/delete/{id}', [PurchaseController::class, 'delete']);
});

Route::prefix('invoice')->group(function () {
    Route::get('/all', [InvoiceController::class, 'getAll']);

    Route::get('/detail/{id}', [InvoiceController::class, 'getDetail']);

    Route::post('/create', [InvoiceController::class, 'create']);

    Route::post('/update/{id}', [InvoiceController::class, 'update']);
    Route::post('/add-payment/{id}', [InvoiceController::class, 'addPayment']);
    Route::post('/transaction-finished/{id}', [InvoiceController::class, 'transactionFinished']);

    Route::post('/delete/{id}', [InvoiceController::class, 'delete']);
});

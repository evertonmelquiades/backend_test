<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StoreController;

Route::middleware('auth.basic')->group(function () {
    Route::resource('books', BookController::class);
    Route::resource('stores', StoreController::class);

    Route::post('stores/{storeId}/books/{bookId}/associate', [StoreController::class, 'associateBook']);
    Route::get('stores/{storeId}/books', [StoreController::class, 'listBooks']);
});

require __DIR__ . '/auth.php';

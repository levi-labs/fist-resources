<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
});

Route::get('/category', function () {
    return view('pages.category.index');
});
Route::controller(App\Http\Controllers\CategoryController::class)->prefix('category')->group(function () {
    Route::get('/', 'index')->name('category.index');
    Route::post('/', 'index')->name('category.search');
    Route::get('/create', 'create')->name('category.create');
    Route::post('/store', 'store')->name('category.store');
    Route::get('/edit/{category}', 'edit')->name('category.edit');
    Route::put('/update/{category}', 'update')->name('category.update');
    Route::get('/delete/{category}', 'destroy')->name('category.destroy');
});

Route::controller(App\Http\Controllers\ProductController::class)->prefix('product')->group(function () {
    Route::get('/', 'index')->name('product.index');
    Route::post('/', 'index')->name('product.search');
    Route::get('/show/{product}', 'show')->name('product.show');
    Route::get('/create', 'create')->name('product.create');
    Route::post('/store', 'store')->name('product.store');
    Route::get('/edit/{product}', 'edit')->name('product.edit');;
    Route::put('/update/{product}', 'update')->name('product.update');
    Route::get('/delete/{product}', 'destroy')->name('product.destroy');
});

Route::controller(App\Http\Controllers\SupplierController::class)->prefix('supplier')->group(function () {
    Route::get('/', 'index')->name('supplier.index');
    Route::post('/', 'index')->name('supplier.search');
    Route::get('/show/{supplier}', 'show')->name('supplier.show');
    Route::get('/create', 'create')->name('supplier.create');
    Route::post('/store', 'store')->name('supplier.store');
    Route::get('/edit/{supplier}', 'edit')->name('supplier.edit');;
    Route::put('/update/{supplier}', 'update')->name('supplier.update');
    Route::get('/delete/{supplier}', 'destroy')->name('supplier.destroy');
});

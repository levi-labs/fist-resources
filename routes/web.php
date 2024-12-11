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

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});
// Route::get('/login', function () {
//     return view('layouts.auth.login');
// })->name('login');
Route::controller(App\Http\Controllers\AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::get('/', 'index')->name('auth.login');
        Route::post('/login', 'login')->name('auth.post');
        Route::get('/logout', 'logout')->name('auth.logout');
    });
Route::middleware(['auth.check', 'role:admin,staff,logistic,procurement'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    })->name('dashboard');
    Route::controller(App\Http\Controllers\CategoryController::class)
        ->prefix('category')
        ->middleware('role:admin,procurement,logistic')
        ->group(function () {
            Route::get('/', 'index')->name('category.index');
            Route::post('/', 'index')->name('category.search');
            Route::get('/create', 'create')->name('category.create');
            Route::post('/store', 'store')->name('category.store');
            Route::get('/edit/{category}', 'edit')->name('category.edit');
            Route::put('/update/{category}', 'update')->name('category.update');
            Route::get('/delete/{category}', 'destroy')->name('category.destroy');
        });

    Route::controller(App\Http\Controllers\ProductController::class)
        ->prefix('product')
        ->middleware('role:admin,procurement,logistic')
        ->group(function () {
            Route::get('/', 'index')->name('product.index');
            Route::post('/', 'index')->name('product.search');
            Route::get('/create', 'create')->name('product.create');
            Route::post('/store', 'store')->name('product.store');
            Route::get('/edit/{product}', 'edit')->name('product.edit');;
            Route::put('/update/{product}', 'update')->name('product.update');
            Route::get('/delete/{product}', 'destroy')->name('product.destroy');
        });
    Route::get('/show/{product}', [App\Http\Controllers\ProductController::class, 'show'])->prefix('product')->name('product.show');
    Route::controller(App\Http\Controllers\SupplierController::class)
        ->prefix('supplier')
        ->middleware('role:admin,procurement,logistic')
        ->group(function () {
            Route::get('/', 'index')->name('supplier.index');
            Route::post('/', 'index')->name('supplier.search');
            Route::get('/show/{supplier}', 'show')->name('supplier.show');
            Route::get('/create', 'create')->name('supplier.create');
            Route::post('/store', 'store')->name('supplier.store');
            Route::get('/edit/{supplier}', 'edit')->name('supplier.edit');;
            Route::put('/update/{supplier}', 'update')->name('supplier.update');
            Route::get('/delete/{supplier}', 'destroy')->name('supplier.destroy');
        });

    Route::controller(App\Http\Controllers\UserController::class)
        ->prefix('user')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/', 'index')->name('user.index');
            Route::post('/', 'index')->name('user.search');
            Route::get('/show/{user}', 'show')->name('user.show');
            Route::get('/create', 'create')->name('user.create');
            Route::post('/store', 'store')->name('user.store');
            Route::get('/edit/{user}', 'edit')->name('user.edit');;
            Route::put('/update/{user}', 'update')->name('user.update');
            Route::get('/delete/{user}', 'destroy')->name('user.destroy');
        });

    Route::controller(App\Http\Controllers\RestockInventoryController::class)
        ->prefix('restock-inventory')
        ->middleware('role:admin,staff,procurement')
        ->group(function () {
            Route::get('/', 'index')->name('restock.inventory.index');
            Route::post('/', 'search')->name('restock.inventory.search');
            Route::get('/approved', 'approved')->name('restock.inventory.approved');
            Route::post('/approved', 'approved')->name('restock.inventory.approvedsearch');
            Route::get('/show/{request_code}', 'show')->name('restock.inventory.show');
            Route::get('/create', 'create')->name('restock.inventory.create');
            Route::post('/create', 'create')->name('restock.inventory.createsearch');
            Route::get('/add-item/{id}', 'addItem')->name('restock.inventory.add');
            Route::get('/update-add-item/{id}', 'updateAddItem')->name('restock.inventory.updateAddItem');
            Route::post('/store', 'store')->name('restock.inventory.store');
            Route::get('/edit/{request_code}', 'edit')->name('restock.inventory.edit');
            Route::put('/update/{request_code?}', 'update')->name('restock.inventory.update');
            Route::get('/delete-request/{request_code}', 'destroy')->name('restock.inventory.destroy');
            Route::get('/delete-item/{id}', 'removeItem')->name('restock.inventory.deleteItem');
            Route::get('/update-delete-item/{id}}', 'removeUpdateItem')->name('restock.inventory.deleteItemDetail');

            Route::get('/approve/{request_code}', 'approve')->name('restock.inventory.approve');
            Route::post('/approve/{request_code}', 'approve')->name('restock.inventory.approvedetail');
            Route::get('/reject', 'rejected')->name('restock.inventory.rejected');
            Route::get('/resubmit', 'resubmitted')->name('restock.inventory.resubmitted');
            Route::put('/resubmit/{request_code}', 'resubmit')->name('restock.inventory.resubmitteddetail');
        });
});

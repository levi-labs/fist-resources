<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/auth');
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
Route::middleware(['auth.check', 'role:admin,staff,logistic,procurement,supplier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
            Route::get('/reset-password/{user}', 'resetPassword')->name('user.resetPassword');
            Route::get('/change-password', 'changePassword')->name('user.changePassword');
            Route::patch('/change-password/{id}', 'updatePassword')->name('user.updatePassword');
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
            Route::get('/update-add-item/{id}/{request_code}', 'updateAndCreate')->name('restock.inventory.updateAddItem');
            Route::post('/store', 'store')->name('restock.inventory.store');
            Route::get('/edit/{request_code}', 'edit')->name('restock.inventory.edit');
            Route::post('/edit/{request_code}', 'edit')->name('restock.inventory.editsearch');
            Route::put('/update/{request_code?}', 'update')->name('restock.inventory.update');
            Route::get('/delete-request/{request_code}', 'destroy')->name('restock.inventory.destroy');
            Route::get('/delete-item/{id}', 'removeItem')->name('restock.inventory.deleteItem');
            Route::get('/update-delete-item/{id}}', 'removeUpdateItem')->name('restock.inventory.deleteItemDetail');

            Route::get('/approve/{request_code}', 'approve')->name('restock.inventory.approve');
            Route::post('/approve/{request_code}', 'approve')->name('restock.inventory.approvedetail');
            Route::get('/rejected', 'rejected')->name('restock.inventory.rejected');
            Route::get('/reject/{request_code}', 'reject')->name('restock.inventory.rejectedetail');
            Route::get('/resubmit', 'resubmitted')->name('restock.inventory.resubmitted');
            Route::put('/resubmit/{request_code}', 'resubmit')->name('restock.inventory.resubmitteddetail');
            Route::get('/print/{request_code}', 'print')->name('restock.inventory.print');
        });

    Route::controller(App\Http\Controllers\ProposedProductController::class)
        ->prefix('propose-product')
        ->middleware('role:admin,staff,procurement')
        ->group(function () {
            Route::get('/', 'index')->name('propose.product.index');
            Route::post('/', 'index')->name('propose.product.search');
            Route::get('/show/{id}', 'show')->name('propose.product.show');
            Route::get('/create', 'create')->name('propose.product.create');
            Route::post('/create', 'store')->name('propose.product.store');
            Route::get('/edit/{id}', 'edit')->name('propose.product.edit');
            Route::put('/update/{id}', 'update')->name('propose.product.update');
            Route::get('/delete/{id}', 'destroy')->name('propose.product.destroy');
        });
    Route::controller(App\Http\Controllers\ProposeInventoryController::class)
        ->prefix('propose-inventory')
        ->middleware('role:admin,staff,procurement')
        ->group(function () {
            Route::get('/', 'index')->name('propose.inventory.index');
            Route::post('/', 'search')->name('propose.inventory.search');
            Route::get('/create', 'create')->name('propose.inventory.create');
            Route::post('/create', 'create')->name('propose.inventory.createsearch');
            Route::get('/show/{request_code}', 'show')->name('propose.inventory.show');
            Route::post('/store', 'store')->name('propose.inventory.store');
            Route::get('/edit/{request_code}', 'edit')->name('propose.inventory.edit');
            Route::put('/update/{request_code}', 'update')->name('propose.inventory.update');
            Route::get('/delete/{id}', 'destroy')->name('propose.inventory.destroy');
            Route::get('/add-item/{id}', 'addItem')->name('propose.inventory.add');
            Route::get('/update-add-item/{id}/{request_code}', 'updateAndCreate')->name('propose.inventory.updateAddItem');
            Route::get('/remove-item/{id}', 'removeItem')->name('propose.inventory.remove');
            Route::get('/print/{request_code}', 'print')->name('propose.inventory.print');

            Route::get('/approve', 'approved')->name('propose.inventory.approved');
            Route::post('/approve/{request_code}', 'approve')->name('propose.inventory.approvedetail');
            Route::get('/rejected', 'rejected')->name('propose.inventory.rejected');
            Route::get('/reject/{request_code}', 'reject')->name('propose.inventory.rejectedetail');
            Route::get('/resubmit', 'resubmitted')->name('propose.inventory.resubmitted');
            Route::put('/resubmit/{request_code}', 'resubmit')->name('propose.inventory.resubmitteddetail');
            Route::get('/delete-item/{id}', 'removeItem')->name('propose.inventory.deleteItem');
            Route::get('/update-delete-item/{id}', 'removeUpdateItem')->name('propose.inventory.deleteItemDetail');
        });
    Route::controller(App\Http\Controllers\PurchaseRestockController::class)
        ->prefix('restock-purchase-order')
        ->middleware('role:admin,staff,procurement,supplier')
        ->group(function () {
            Route::get('/', 'index')->name('restock.purchase.index');
            Route::post('/', 'index')->name('restock.purchase.search');
            Route::get('/show/{id}', 'show')->name('restock.purchase.show');
            Route::get('/create', 'create')->name('restock.purchase.create');
            Route::post('/create', 'store')->name('restock.purchase.store');
            Route::get('/shipped', 'shipped')->name('restock.purchase.shipped');
            Route::post('/shipped', 'shipped')->name('restock.purchase.shippedsearch');
            Route::get('/delivered', 'delivered')->name('restock.purchase.delivered');
            Route::post('/delivered', 'delivered')->name('restock.purchase.deliveredsearch');
            Route::get('/print/{id}', 'print')->name('restock.purchase.print');
        });
    Route::controller(App\Http\Controllers\PurchaseProposeController::class)
        ->prefix('propose-purchase-order')
        ->middleware('role:admin,logistic,procurement,supplier')
        ->group(function () {
            Route::get('/', 'index')->name('propose.purchase.index');
            Route::post('/', 'index')->name('propose.purchase.search');
            Route::get('/show/{id}', 'show')->name('propose.purchase.show');
            Route::get('/create', 'create')->name('propose.purchase.create');
            Route::post('/create', 'store')->name('propose.purchase.store');
            Route::get('/shipped', 'shipped')->name('propose.purchase.shipped');
            Route::post('/shipped', 'shipped')->name('propose.purchase.shippedsearch');
            Route::get('/delivered', 'delivered')->name('propose.purchase.delivered');
            Route::post('/delivered', 'delivered')->name('propose.purchase.deliveredsearch');
            Route::get('/print/{id}', 'print')->name('propose.purchase.print');
        });
    Route::controller(App\Http\Controllers\ShipmentController::class)
        ->prefix('shipment')
        ->middleware('role:admin,procurement,logistic,supplier')
        ->group(function () {
            Route::post('/create', 'store')->name('shipment.store');
            Route::get('/restock', 'restockShipped')->name('shipment.restockShipped');
            Route::post('/restock', 'restockShipped')->name('shipment.restockShippedsearch');
            Route::get('/restock-delivered', 'restockDelivered')->name('shipment.restockDelivered');
            Route::post('/restockdelivered', 'restockDelivered')->name('shipment.restockDeliveredsearch');
            Route::get('/propose', 'proposeShipped')->name('shipment.proposeShipped');
            Route::post('/propose', 'proposeShipped')->name('shipment.proposeShippedsearch');
            Route::get('/propose/delivered', 'proposeDelivered')->name('shipment.proposeDelivered');
            Route::post('/propose/delivered', 'proposeDelivered')->name('shipment.proposeDeliveredsearch');
        });
    Route::controller(App\Http\Controllers\GoodReceivedController::class)
        ->prefix('good-received')
        ->middleware('role:admin,logistic')
        ->group(function () {
            Route::get('/', 'index')->name('goods.received.index');
            Route::post('/index', 'search')->name('goods.received.search');
            Route::get('/create', 'create')->name('goods.received.create');
            Route::post('/create', 'trackingNumber')->name('goods.received.tracking');
            Route::post('/store', 'store')->name('goods.received.store');
            Route::get('/show/{id}', 'show')->name('goods.received.show');
        });
    Route::controller(App\Http\Controllers\InventoryController::class)
        ->prefix('inventory')
        ->middleware('role:admin,procurement,logistic')
        ->group(function () {
            Route::get('/', 'index')->name('inventory.index');
            Route::post('/', 'index')->name('inventory.search');
        });
    Route::controller(App\Http\Controllers\ReportController::class)
        ->prefix('report')
        ->middleware('role:admin,procurement')
        ->group(function () {
            Route::get('/report-restock-purchase', 'reportRestockView')->name('report.restock.purchase');
            Route::post('/report-restock-purchase', 'reportRestockSearch')->name('report.restock.purchase.search');
            Route::get('/report-restock-purchase-print', 'reportRestockPurchasePrint')->name('report.restock.purchase.print');

            Route::get('/report-propose-purchase', 'reportProposeView')->name('report.propose.purchase');
            Route::post('/report-propose-purchase', 'reportProposeSearch')->name('report.propose.purchase.search');
            Route::get('/report-propose-purchase-print', 'reportProposePurchasePrint')->name('report.propose.purchase.print');
        });
});

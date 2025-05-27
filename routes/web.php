<?php

use App\Models\Extra;
use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use App\Models\Admin\DelivaryCharge;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaytrailController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [FrontendController::class, 'index'])->name('index');
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
   //Product Management
    Route::group(['middleware' => ['permission:Product Management']], function () {
        Route::prefix('product')->middleware(['auth'])->group(function () {
            Route::resource('products', ProductContoller::class);
        }); 
    }); 
        
    //Product Management
    Route::group(['middleware' => ['permission:Purchase Management']], function () {
        Route::prefix('purchase')->middleware(['auth'])->group(function () {
            Route::resource('purchase', PurchaseController::class);
        }); 
    });


    //Order Management
    Route::group(['middleware' => ['permission:content-management']], function () {
        Route::resource('slider', SliderController::class);
        Route::resource('home-ad', AdsController::class);
    });


    Route::group(['middleware' => ['permission:Administration']], function () {
        Route::resource('users', UserController::class);
        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);
        Route::resource('attendance', AttendanceController::class);

        Route::get('/user/pin', [UserController::class,'pin'])->name('users.pin');
        Route::post('/user/pin', [UserController::class,'pinStore'])->name('users.pin_store');
    });

    Route::group(['middleware' => ['permission:Service Management']], function () {
        Route::resource('service', ServiceController::class);
        Route::get('service/invoice/{id}', [ServiceController::class, 'makeInvoice'])->name('service.invoice');
        Route::get('complated/service', [ServiceController::class, 'complatedService'])->name('service.complated');
        Route::post('service/makecomplate/{id}', [ServiceController::class, 'makeComplate'])->name('service.makecomplate');
        Route::get('service-payments', [ServiceController::class, 'payments'])->name('service.payments');
        Route::post('/submit-rating', [ServiceController::class, 'storeRating'])->name('submit.rating');

    });
    Route::group(['middleware' => ['permission:Sales Management']], function () {
        Route::resource('sales', SalesController::class);
        Route::get('sales/invoice/{id}', [SalesController::class, 'makeInvoice'])->name('sales.invoice');
        Route::get('sales-payments', [SalesController::class, 'payments'])->name('sales.payments');
    });

    Route::resource('dailySales', DailySaleController::class);
    Route::resource('salesTarget', SalesTargetController::class);
    Route::resource('dailyExpenses', ExpenseController::class);

    
    // Route::group(['middleware' => ['permission:Service Management|Sales Management']], function () {
    //     Route::get('payments/{id}/{payment_for}', [PaymentController::class, 'payments'])->name('payments');
    //     Route::post('add/payment', [PaymentController::class, 'addPayment'])->name('add.payment');
    //     Route::post('update/payment/{id}', [PaymentController::class, 'updatePayment'])->name('update.payment');
    //     Route::delete('delete/payment/{id}', [PaymentController::class, 'deletePayment'])->name('delete.payment');
    //     Route::resource('products', ProductContoller::class);
    // });
 
});


Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);



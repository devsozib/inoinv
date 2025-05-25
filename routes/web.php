<?php

use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use App\Models\Admin\DelivaryCharge;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Location;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaytrailController;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\SalesTargetController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductContoller;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\WriterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReviewRatingController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopingsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\NutritionController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\OptionTitleController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BookCategoryController;
use App\Http\Controllers\Admin\TimeScheduleController;
use App\Http\Controllers\Admin\DelivaryChargeController;
use App\Http\Controllers\Admin\BookSubCategoryController;
use App\Http\Controllers\Admin\ProductMnagementController;
use App\Http\Controllers\Admin\DelivaryPercentageController;

use App\Models\Extra;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();


Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/services', [FrontendController::class, 'ourServices'])->name('services');
Route::get('/projects', [FrontendController::class, 'projects'])->name('projects');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/item/details/{id}', [FrontendController::class, 'productDetails'])->name('productDetails');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('/get-cart', [FrontendController::class, 'getCart'])->name('get_cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::post('/get-checkout-page', [FrontendController::class, 'getCheckOutPage'])->name('get_check_out_page');
Route::get('/filter-item', [FrontendController::class, 'filterProduct'])->name('filterProduct');
Route::post('/filter', [FrontendController::class, 'filter'])->name('filter');
Route::post('/store/review', [ReviewRatingController::class, 'storeReview'])->name('store_ratings_reviews');
Route::post('/free_search_product', [FrontendController::class, 'freeSearch'])->name('free_search_product');
Route::post('save_reference_number', [OrderController::class, 'savePaymentInfo'])->name('save_reference_number');





Route::get('/subjects', [FrontendController::class, 'subjects'])->name('subjects');
Route::get('/subject/{id}', [FrontendController::class, 'subjectWiseProducts'])->name('subjectWiseProducts');
Route::get('/authors', [FrontendController::class, 'authors'])->name('authors');
Route::get('/author/{id}', [FrontendController::class, 'authorWiseProducts'])->name('authorWiseProducts');
Route::get('/pulishers', [FrontendController::class, 'pulishers'])->name('pulishers');
Route::get('/pulisher/{id}', [FrontendController::class, 'pulisherWiseProducts'])->name('pulisherWiseP
roducts');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [FrontendController::class, 'index'])->name('index');
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
   
    Route::group(['middleware' => ['permission:Product Management']], function () {
        Route::prefix('product')->middleware(['auth'])->group(function () {
            Route::resource('products', ProductContoller::class);
        }); 
    }); 
        
    //Order Management
    Route::group(['middleware' => ['permission:content-management']], function () {
        Route::resource('slider', SliderController::class);
        Route::resource('home-ad', AdsController::class);
    });
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
 
   


Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);



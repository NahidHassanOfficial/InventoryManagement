<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResetTokenMiddleware;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('loginPage');
});

route::get('/logout', [UserController::class, 'userLogout'])->name('userLogout');

Route::middleware(RedirectIfAuthenticated::class
)->group(function () {
    //auth page
    Route::get('/login', [UserController::class, 'loginPage'])->name('loginPage');
    Route::get('/register', [UserController::class, 'registerPage'])->name('registerPage');
    Route::get('/reset-password', [UserController::class, 'resetPwdPage'])->name('resetPwdPage');
    Route::get('/reset-form', [UserController::class, 'resetFormPage'])->name('resetFormPage')->middleware(ResetTokenMiddleware::class);
    Route::get('/verify-otp', [UserController::class, 'OTPVerifyPage'])->name('OTPVerifyPage');

    //auth api
    route::post('/register', [UserController::class, 'userRegister'])->name('userRegister')->middleware('throttle:3,1');
    route::post('/login', [UserController::class, 'userLogin'])->name('userLogin')->middleware('throttle:10,1');

    //auth reset api
    Route::post('/send-otp', [UserController::class, 'sendOtp'])->name('sendOtp')->middleware('throttle:5,10');
    Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verifyOtp')->middleware('throttle:2,1');
    route::post('/reset-password', [UserController::class, 'resetPwd'])->name('resetPwd')->middleware(ResetTokenMiddleware::class);
});

//dashboard page
Route::group(['middleware' => TokenVerificationMiddleware::class], function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/customer', [CustomerController::class, 'customer'])->name('customer');
    Route::get('/category', [CategoryController::class, 'category'])->name('category');
    Route::get('/product', [ProductController::class, 'product'])->name('product');
    Route::get('/create-order', [InvoiceController::class, 'createOrder'])->name('createOrder');
    Route::get('/invoice-list', [InvoiceController::class, 'invoiceList'])->name('invoiceList');
    Route::get('/sales-report', [SalesReportController::class, 'salesReport'])->name('salesReport');
});

//dashboard page initial data fetch
Route::middleware(TokenVerificationMiddleware::class)
    ->group(function () {
        Route::get('/customerList', [CustomerController::class, 'customerList'])->name('customer.data');
        Route::get('/categoryList', [CategoryController::class, 'categoryList'])->name('category.data');
        Route::get('/productList', [ProductController::class, 'productList'])->name('product.data');
        Route::get('/orderList', [InvoiceController::class, 'createOrder'])->name('createOrder.data');
        Route::get('/invoiceList', [InvoiceController::class, 'invoiceList'])->name('invoice.data');
        Route::get('/salesReport', [SalesReportController::class, 'salesReport'])->name('sales.data');
    });

//dashboard manage data
Route::middleware(TokenVerificationMiddleware::class)
    ->group(function () {
        //User api manage user
        Route::get('/profile-info', [UserController::class, 'profileInfo'])->name('user.info');
        Route::patch('/profile', [UserController::class, 'profileUpdate'])->name('user.update');
        Route::get('/shop-info', [ShopController::class, 'shopInfo'])->name('shop.info');
        Route::post('/shop/update', [ShopController::class, 'shopUpdate'])->name('shop.update');

        //dashboard api manage customer
        Route::post('/customer', [CustomerController::class, 'customerCreate'])->name('customer.create');
        Route::get('/customer-info', [CustomerController::class, 'customerInfo'])->name('customer.info');
        Route::patch('/customer', [CustomerController::class, 'customerUpdate'])->name('customer.update');
        Route::delete('/customer', [CustomerController::class, 'customerDelete'])->name('customer.delete');

        //dashboard api manage category
        Route::post('/category', [CategoryController::class, 'categoryCreate'])->name('category.create');
        Route::get('/category-info', [CategoryController::class, 'categoryInfo'])->name('category.info');
        Route::patch('/category', [CategoryController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category', [CategoryController::class, 'categoryDelete'])->name('category.delete');

        //dashboard api manage product
        Route::post('/product', [ProductController::class, 'productCreate'])->name('product.create');
        Route::get('/product-info', [ProductController::class, 'productInfo'])->name('product.info');
        Route::post('/product/update', [ProductController::class, 'productUpdate'])->name('product.update');
        Route::delete('/product', [ProductController::class, 'productDelete'])->name('product.delete');

        //dashboard api create sale & manage invoice
        Route::post('/invoice', [InvoiceController::class, 'invoiceCreate'])->name('invoice.create');
        Route::get('/all-invoices', [InvoiceController::class, 'allInvoice'])->name('all.invoice');
        Route::get('/invoice-info', [InvoiceController::class, 'invoiceInfo'])->name('invoice.info');
        Route::delete('/invoice', [InvoiceController::class, 'invoiceDelete'])->name('invoice.delete');

        //dashboard api sales report
        Route::get('/sales-report/{fromDate}/{toDate}', [SalesReportController::class, 'generateReport']);

        //dashboard api total data
        Route::get('/dashboard-summary', [DashboardController::class, 'totalSummary'])->name('dashboard.data');
    });

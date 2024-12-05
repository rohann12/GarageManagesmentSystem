<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\WebLoginController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\RepairController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::get('/', function () {
    return view('welcome');
});


// Authentication Routes
Route::controller(RegisterController::class)->group(function () {
    Route::get('register', 'showRegistrationForm')->name('register');
    Route::post('register', 'register');
});

Route::controller(WebLoginController::class)->middleware('auth')->group(function () {
    Route::post('logout', 'logout')->name('logout');
    Route::get('user/profile', 'edit')->name('user.edit');
    Route::put('user/update/{user}', 'update')->name('user.update');
    Route::put('changePassword/{user}', 'changePassword')->name('changePassword');
});

Route::controller(WebLoginController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/home', function () {
    //     return view('home');
    // })->name('home');
});
Route::get('/home', function () {
    return view('home');
})->name('home');

// Order Routes
Route::middleware('auth')->controller(OrderController::class)->group(function () {
    Route::get('/orders', 'index')->name('orders.index');
    Route::get('/orders/create', 'create')->name('orders.create');
    Route::post('/orders', 'store')->name('orders.store');
    Route::get('/orders/{order}', 'show')->name('orders.show');
    Route::get('/orders/{id}/complete', 'complete')->name('orders.complete');
    Route::get('invoice', 'invoiceIndex')->name('invoice');
    Route::post('/invoices/status', [OrderController::class, 'changeStatus'])->name('invoices.changeStatus');

    Route::get('/orders/{order}/edit', 'edit')->name('orders.edit');
    Route::put('/orders/{order}', 'update')->name('orders.update');
    Route::delete('/orders/{order}', 'destroy')->name('orders.destroy');
    Route::post('/orders/{order}/create-invoice', [OrderController::class, 'createInvoice'])->name('order.createInvoice');
});

// Resource Routes for Employees and Customers
Route::middleware('auth')->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('repairs', RepairController::class);
    Route::resource('parts', PartController::class);
    Route::get('/profile', [EmployeeController::class, 'Profile'])->middleware('auth')->name('profile');

});

Route::get('/invoices/{invoice}', [OrderController::class, 'showInvoice'])->name('showInvoice');
use App\Http\Controllers\NotificationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});



// Or for API routes
Route::post('/api/logout', [LoginController::class, 'logout'])->middleware('auth:api')->name('logout');


Route::get('/esewa', function () {
    return view('esewa');
});

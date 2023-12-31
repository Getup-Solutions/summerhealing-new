<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicPagesController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\AdminDashboardController;

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

Route::get('/', [PublicPagesController::class,'home']);

Route::name('public.')->group(function () {
    Route::get('/', [PublicPagesController::class, 'homePage'])->name('home');
    Route::get('/subscriptions', [PublicPagesController::class, 'subscriptionsPage'])->name('subscriptions');
    Route::get('/courses', [PublicPagesController::class, 'coursesPage'])->name('courses');
    Route::get('/wellness-center', [PublicPagesController::class, 'facilitiesPage'])->name('facilities');
    Route::get('/trainings', [PublicPagesController::class, 'trainingsPage'])->name('trainings');
    // Route::get('/subscriptions', [PublicPagesController::class, 'subscriptionPage'])->name('subscription');
    Route::get('/about', [PublicPagesController::class, 'aboutPage'])->name('about');
    Route::get('/contact', [PublicPagesController::class, 'contactPage'])->name('contact');
    Route::get('/subscriptions/{subscription:slug}', [PublicPagesController::class, 'subscriptionSinglePage'])->name('subscription_single');
    Route::get('/courses/{course:slug}', [PublicPagesController::class, 'courseSinglePage'])->name('course_single');
    Route::get('/wellness-center/{facility:slug}', [PublicPagesController::class, 'facilitySinglePage'])->name('facility_single');

    // Route::get('/pages/{customPage:slug}', [PublicPagesController::class, 'customPage'])->name('customPage');


    Route::name('account.')->group(function(){
        Route::get('login', [LoginController::class, 'login'])->middleware('guest')->name('login');
        Route::post('login', [LoginController::class, 'auth'])->middleware('guest');
        Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

        Route::get('register', [LoginController::class, 'create'])->middleware('guest')->name('register');
        Route::post('register', [LoginController::class, 'store'])->middleware('guest');
    });

    Route::name('products.')->group(function(){
        Route::get('/shop', [ProductController::class, 'index'])->name('index');
        Route::get('products/{product:slug}', [ProductController::class, 'show'])->name('show');
    });

    Route::name('cart.')->group(function(){
        // Route::get('cart/', [CartController::class, 'index'])->name('index');
        Route::post('cart/add', [CartController::class, 'add'])->name('add');
        Route::post('cart/update', [CartController::class, 'update'])->name('update');
        Route::post('cart/remove', [CartController::class, 'remove'])->name('remove');
    });

    Route::middleware('auth')->group(function () {
        Route::name('dashboard.')->group(function(){
            Route::prefix('dashboard')->group(function(){
                Route::get('/', [CustomerDashboardController::class, 'index'])->name('home');
                Route::get('/address', [CustomerDashboardController::class, 'address'])->name('address');
                Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
                Route::post('/{user}', [CustomerDashboardController::class, 'update']);
            });
        });
        Route::name('checkout.')->group(function(){
            Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
            Route::post('checkout', [CheckoutController::class, 'checkout']);
            Route::post('checkout/webhook', [CheckoutController::class, 'webhook']);
            Route::get('checkout/success', [CheckoutController::class, 'success'])->name('success');
            Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
        });
    });
});

Route::name('admin.')->group(function () {
    Route::prefix('/admin')->group(function(){
        Route::name('auth.')->group(function () {
            Route::get('/login',[AuthController::class,'login'])->middleware('guest')->name('login');
            Route::post('/login',[AuthController::class,'auth'])->middleware('guest');
            Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth', 'role:ADMIN_ROLE'])->name('logout');
        });


        Route::name('dashboard.')->group(function () {
            Route::middleware(['auth', 'role:ADMIN_ROLE'])->group(function () {
                Route::prefix('/dashboard')->group(function () {
                    Route::get('/', [AdminDashboardController::class, 'home'])->name('home');
                    Route::resource('/users', UserController::class)->except('show');
                    Route::resource('/courses', CourseController::class)->except('show');
                    Route::resource('/facilities', FacilityController::class)->except('show');
                    Route::resource('/trainers', TrainerController::class)->except(['show','store']);
                    Route::resource('/subscriptions', SubscriptionController::class)->except('show');
                    Route::resource('/leads', LeadController::class)->except('show');
                    Route::resource('/trainings', TrainingController::class)->except('show');

                    // Route::resource('/users', UserController::class)->except('show');
                    // Route::get('/profile-info', [AdminDashboardController::class, 'profileInfo'])->name('profile_info');
                    // Route::put('/profile-info', [AdminDashboardController::class, 'update'])->name('profile_info_update');
                });
            });
        });
    });
    // Route::middleware(['auth', 'role:ADMIN_ROLE'])->group(function () {
    //     Route::prefix('/admin-dashboard')->group(function () {
    //         Route::get('/', [AdminDashboardController::class, 'home'])->name('home');
    //         Route::resource('/users', UserController::class)->except('show');
    //         Route::get('/profile-info', [AdminDashboardController::class, 'profileInfo'])->name('profile_info');
    //         Route::put('/profile-info', [AdminDashboardController::class, 'update'])->name('profile_info_update');
    //     });
    // });
});



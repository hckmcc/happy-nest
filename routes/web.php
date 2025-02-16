<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [MainPageController::class, 'index'])->name('home');

Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/ads/{ad}', [AdController::class, 'showAd'])->name('ad.show');

Route::get('/user/{user}', [SellerController::class, 'showSeller'])->name('seller.show');

Route::get('/categories', [CategoryController::class, 'showCategoriesList'])->name('categories');
Route::get('/categories/{category}', [CategoryController::class, 'showCategory'])->name('category.show');

Route::middleware('auth.modal')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::get('/my_ads', [AdController::class, 'showMyAds'])->name('my_ads');
    Route::get('/create_ad', [AdController::class, 'showCreateAdForm'])->name('create_ad');
    Route::post('/create_ad', [AdController::class, 'createAd']);
    Route::get('/ads/promote/{ad}', [AdController::class, 'showAdPromotePage'])->name('ad.promote.show');
    Route::post('/ads/{ad}/complete', [AdController::class, 'complete'])->name('ad.complete');
    Route::post('/ads/{ad}/republish', [AdController::class, 'republish'])->name('ad.republish');
    Route::put('/ads/{ad}/update', [AdController::class, 'update'])->name('ad.update');
    Route::delete('/ads/{ad}/delete', [AdController::class, 'delete'])->name('ad.delete');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ad.edit');

    Route::post('/reviews', [ReviewController::class, 'add'])->name('reviews.add');

    Route::get('/chats', [ChatController::class, 'showMyChats'])->name('my_chats');
    Route::post('/chats', [ChatController::class, 'add'])->name('chats.add');
    Route::get('/chats/{ad}/{user}', [ChatController::class, 'showChat'])->name('chat.show');

    Route::get('/favourites', [FavouritesController::class, 'showFavourites'])->name('favourites');
    Route::post('/favourites/{ad}', [FavouritesController::class, 'add'])->name('favourites.add');

    Route::post('/report', [ReportController::class, 'store'])->name('reports.add');

    Route::post('/payment/{ad}/create', [PaymentController::class, 'create'])->name('payment.create');
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'showUsers'])->name('admin.users');
    Route::get('/users/add', [\App\Http\Controllers\Admin\UserController::class, 'showUserAddPage'])->name('admin.user.add.show');
    Route::post('/users/add', [RegistrationController::class, 'register'])->name('admin.user.add');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'showUserPage'])->name('admin.user.show');
    Route::delete('/users/{user}/delete', [\App\Http\Controllers\Admin\UserController::class, 'deleteUser'])->name('admin.user.delete');
    Route::put('/users/{user}/roles', [\App\Http\Controllers\Admin\UserController::class, 'updateUserRoles'])->name('admin.user.updateRoles');

    Route::get('/ads', [\App\Http\Controllers\Admin\AdController::class, 'showAds'])->name('admin.ads');
    Route::get('/ads/add', [\App\Http\Controllers\Admin\AdController::class, 'showAdCreatePage'])->name('admin.ad.create.show');
    Route::post('/ads/add', [AdController::class, 'createAd'])->name('admin.ad.create');
    Route::get('/ads/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'showAdPage'])->name('admin.ad.show');
    Route::delete('/ads/{ad}/delete', [AdController::class, 'delete'])->name('admin.ad.delete');

    Route::delete('/reviews/{review}/delete', [ReviewController::class, 'delete'])->name('admin.review.delete');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'showReports'])->name('admin.reports');
    Route::delete('/reports/{report}/delete', [\App\Http\Controllers\Admin\ReportController::class, 'deleteReport'])->name('admin.report.delete');

    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'showCategories'])->name('admin.categories');
    Route::get('/categories/add', [\App\Http\Controllers\Admin\CategoryController::class, 'showCategoryCreatePage'])->name('admin.category.create.show');
    Route::post('/categories/add', [\App\Http\Controllers\Admin\CategoryController::class, 'createCategory'])->name('admin.category.create');
    Route::delete('/categories/{category}/delete', [\App\Http\Controllers\Admin\CategoryController::class, 'deleteCategory'])->name('admin.category.delete');
    Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'showCategoryEditPage'])->name('admin.category.edit.show');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateCategory'])->name('admin.category.update');
});

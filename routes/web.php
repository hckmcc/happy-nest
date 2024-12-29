<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainPageController;
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
Route::middleware('auth.modal')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::get('/my_ads', [AdController::class, 'showMyAds'])->name('my_ads');
    Route::get('/create_ad', [AdController::class, 'showCreateAdForm'])->name('create_ad');
    Route::post('/create_ad', [AdController::class, 'createAd']);
    Route::post('/ads/{ad}/complete', [AdController::class, 'complete'])->name('ad.complete');
    Route::post('/ads/{ad}/republish', [AdController::class, 'republish'])->name('ad.republish');
    Route::put('/ads/{ad}/update', [AdController::class, 'update'])->name('ad.update');
    Route::delete('/ads/{ad}/delete', [AdController::class, 'delete'])->name('ad.delete');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ad.edit');
    Route::post('/reviews', [ReviewController::class, 'add'])->name('reviews.add');
    Route::get('/chats', [ChatController::class, 'showMyChats'])->name('my_chats');
    Route::get('/chats/{ad}/{user}', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chats', [ChatController::class, 'add'])->name('chats.add');
    Route::get('/favourites', [FavouritesController::class, 'showFavourites'])->name('favourites');
    Route::post('/favourites/{ad}', [FavouritesController::class, 'add'])->name('favourites.add');
    Route::post('/report', [ReportController::class, 'store'])->name('reports.add');
});
Route::prefix('/admin')->group(function(){
    Route::get('/login', [LoginController::class, 'login']);
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/users', [LoginController::class, 'login'])->name('login');
    Route::get('/ads', [LoginController::class, 'login'])->name('login');
});


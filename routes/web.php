<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\WebinarController;
use App\Http\Controllers\Admin\GotoauthController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/admin/gotoauth', [GotoauthController::class,'edit'])->name('gotoauth.edit');
    Route::put('/admin/gotoauth', [GotoauthController::class,'update'])->name('gotoauth.update');
    Route::get('/admin/gotoauth/login', [GotoauthController::class,'getAccess'])->name('gotoauth.getAccess');
    Route::post('/admin/gotoauth/refreshToken', [GotoauthController::class,'refreshToken'])->name('gotoauth.refreshToken');
    Route::get('/OAuth2/callback', [GotoauthController::class,'OAuthCallback']);



    Route::match(['get', 'post'], '/admin/webinar/register/{webinar}', [WebinarController::class,'register'])->name('webinar.register');
    Route::get('/admin/webinar/create',         [WebinarController::class, 'create'])->name('webinar.create');
    Route::get('/admin/webinar/{webinar}/edit', [WebinarController::class, 'edit'])->name('webinar.edit');
    Route::get('/admin/webinar',                [WebinarController::class, 'index'])->name('webinar.index');
    Route::post('/admin/webinar',               [WebinarController::class, 'store'])->name('webinar.store');
    Route::put('/admin/webinar/{webinar}',      [WebinarController::class, 'update'])->name('webinar.update');
    Route::delete('/admin/webinar/{webinar}',   [WebinarController::class, 'destroy'])->name('webinar.cancel');
});




require __DIR__.'/auth.php';











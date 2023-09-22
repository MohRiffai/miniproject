<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

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

route ::get(uri: '/', action: \App\Http\Controllers\HomeController::class)->name(name:'home');
// route ::get(uri: 'articles', action: [\App\Http\Controllers\ArticleController::class, 'index'])->name(name:'articles.index');
route::get('/articles/article/checkSlug', [ArticleController::class, 'checkSlug']);

Route::middleware('auth')->group(function () {
    route::get(uri: 'dashboard', action: \App\Http\Controllers\DashboardController::class)->name(name:'dashboard');
    route::resource(name: 'users', controller: \App\Http\Controllers\UserController::class);
    route::resource(name: 'categories', controller: \App\Http\Controllers\CategoryController::class);
    route::resource(name: 'tags', controller: \App\Http\Controllers\TagController::class);
    route::resource(name: 'articles', controller: \App\Http\Controllers\ArticleController::class);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

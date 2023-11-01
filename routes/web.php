<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

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

route::get(uri: '/', action: \App\Http\Controllers\HomeController::class)->name(name: 'home');
// route ::get(uri: 'articles', action: [\App\Http\Controllers\ArticleController::class, 'index'])->name(name:'articles.index');
route::get('/articles/article/checkSlug', [ArticleController::class, 'checkSlug']);

Route::middleware(['auth'])->group(function () {
    route::get(uri: 'dashboard', action: \App\Http\Controllers\DashboardController::class)->name(name: 'dashboard');
    route::resource(name: 'users', controller: \App\Http\Controllers\UserController::class);
    route::resource(name: 'categories', controller: \App\Http\Controllers\CategoryController::class);
    route::resource(name: 'tags', controller: \App\Http\Controllers\TagController::class);
    route::resource(name: 'articles', controller: \App\Http\Controllers\ArticleController::class);
    Route::get('/roles/manage', [RoleController::class, 'manage'])->name('roles.manage');
    Route::post('roles/assignRole', [RoleController::class, 'assignRole'])->name('role.assign');
    Route::delete('/remove-role/{user_name}/{role_name}', [RoleController::class, 'removeRole'])->name('remove.role');
    Route::post('/roles/givePermission', [RoleController::class, 'givePermission'])->name('roles.givePermission');
    Route::delete('/roles/{role}/revoke-permission/{permission}', [RoleController::class, 'revokePermission'])->name('roles.permissions.revoke');
    route::resource(name: 'roles', controller: \App\Http\Controllers\RoleController::class);
    route::resource(name: 'permissions', controller: \App\Http\Controllers\PermissionController::class);

    Route::middleware(['can:viewAny,App\User'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['can:viewAny,App\User,App\Role'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('/roles/manage', [RoleController::class, 'manage'])->name('roles.manage');
        Route::post('roles/assignRole', [RoleController::class, 'assignRole'])->name('role.assign');
        Route::delete('/remove-role/{user_name}/{role_name}', [RoleController::class, 'removeRole'])->name('remove.role');
        Route::post('/roles/givePermission', [RoleController::class, 'givePermission'])->name('roles.givePermission');
        Route::delete('/roles/{role}/revoke-permission/{permission}', [RoleController::class, 'revokePermission'])->name('roles.permissions.revoke');
    });

    Route::middleware(['can:viewAny,App\User,App\Permission'])->group(function () {
        Route::resource('permissions', PermissionController::class);
    });

    // Route::middleware(['can:viewAny,App\User,App\Article'])->group(function () {
    //     Route::resource('articles', ArticleController::class);
    // });

    


    // Menambahkan middleware 'can' untuk mengatur izin akses pada artikel
    // Route::middleware('can:update,article')->group(function () {
    //     route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    //     route::put('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    // });

    // // Menambahkan middleware 'can' untuk mengatur izin akses pada penghapusan artikel
    // Route::middleware('can:delete,article')->group(function () {
    //     route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    // });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

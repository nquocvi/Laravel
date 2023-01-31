<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Users\LoginController;
use App\Http\Controllers\Admin\Users\RegisterController;
use App\Http\Controllers\Admin\Users\LogoutController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\UserMainController;


Route::get('/', function () {
    return view('admin.users.login');
});

Route::get('admin/users/login', [LoginController::class, 'index'])->name('login');
Route::post('admin/users/login/store', [LoginController::class, 'store']);
Route::get('/admin/users/register', [RegisterController::class, 'index']);
Route::get('/admin/users/register/store', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    
    Route::prefix('admin')->group(function () {

        Route::get('main',[MainController::class,'index'])->name('admin');

        Route::prefix('category')->group(function () {
            Route::get('add',[CategoryController::class,'index']);
            Route::post('add',[CategoryController::class,'store']);
            Route::get('view',[CategoryController::class,'viewCategories']);
        });

        Route::prefix('users')->group(function () {
            Route::get('logout',[LogoutController::class,'index']);
            Route::get('manage',[UserController::class,'getUsers'])->name('getUsers');
            Route::get('delete-user/{id}',[UserController::class,'deleteUser']);
            Route::get('edit-user/{id}',[UserController::class,'getUser'])->name('getUser');
            Route::post('edit-user/{id}',[UserController::class,'updateUser']);
            Route::get('users-import', [UserController::class,'importUser'])->name('users.importUser');
            Route::post('users-import', [UserController::class,'importCsvBatch'])->name('users.import');
            Route::get('users-export', [UserController::class,'export'])->name('users.export');
            Route::get('users-import-detail/{id}',[UserController::class,'detailImport']);
            Route::post('multipleusersdelete', [UserController::class,'deleteMultipleUsers']);
        });

    });

    Route::get('user/main',[UserMainController::class,'index'])->name('user');
    
});

<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\category\CategoryController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Role\RoleController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Auth
 */
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register');
    Route::post('forgotPassword','forgotPassword');
    Route::post('resetpassword','resetpassword')->name('password.reset');
    Route::middleware('auth:api')->group(function (){
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });
});

/**
 * Profil
 */

Route::group(['controller' => UserController::class, 'prefix' => 'v1/users', 'middleware' => 'auth:api'], function () {
    Route::get('', 'index')->middleware(['permission:view my profil|view all profil']);
    Route::put('updateNameEmail/{user}', 'updateNameEmail')->middleware(['permission:edit my profil|edit all profil']);
    Route::put('updatePassword/{user}', 'updatePassword')->middleware(['permission:edit my profil|edit all profil']);
    Route::delete('/{user}', 'destroy')->middleware(['permission:delete my profil|delete all profil']);
    Route::put('changerole/{user}', 'changeRole')->middleware(['permission:change role user']);
});

/**
 * Category
 */

Route::group(['controller' => CategoryController::class, 'prefix' => 'v1/categories','middleware'=>'auth:api'], function () {
    Route::get('', 'index')->middleware(['permission:view category']);
    Route::post('', 'store')->middleware(['permission:add category']);
    Route::get('/{category}', 'show')->middleware(['permission:view category']);
    Route::put('/{category}', 'update')->middleware(['permission:edit category']);
    Route::delete('/{category}', 'destroy')->middleware(['permission:delete category']);
    Route::delete('/products/{category}', 'ProductCategory');
});

/**
 * Product
 */

Route::group(['controller' => ProductController::class, 'prefix' => 'v1/products','middleware'=>'auth:api'], function () {
    Route::post('', 'store')->middleware(['permission:add product']);
    Route::put('/{product}', 'update')->middleware(['permission:edit All product|edit My product']);
    Route::delete('/{product}', 'destroy')->middleware(['permission:delete All product|delete My product']);
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{product}', 'show');
});

/**
 * Role
 */

Route::group(['controller' => RoleController::class, 'prefix' => 'v1/roles','middleware'=>'auth:api'], function () {
    Route::get('', 'index')->middleware(['permission:view role']);
    Route::post('', 'store')->middleware(['permission:add role']);
    Route::get('/{role}', 'show')->middleware(['permission:view role']);
    Route::put('/{role}', 'update')->middleware(['permission:edit role']);
});

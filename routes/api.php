<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

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

Route::post('/login',[AuthController::class,'login']);


    Route::post('/register',[AuthController::class,'register']);

    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/products/create',[ProductController::class,'create_product']);
    Route::post('/products/edit/{product_id}',[ProductController::class,'edit_product']);
    Route::delete('/products/delete/{product_id}', [ProductController::class,'delete']);
    



Route::get('/products/show/{product_id}',[ProductController::class,'show_product']);
Route::get('/products',[ProductController::class,'index']);


Route::post('/admin/login',[AdminController::class,'login']);

    Route::post('admin/add_admin',[AdminController::class,'add_admin']);

    Route::post('/admin/logout',[AdminController::class,'logout']);
    Route::post('/products/add',[AdminController::class,'add_category']);
    Route::post('admin/add_role',[AdminController::class,'add_role']);
    Route::post('admin/add_country',[AdminController::class,'add_country']);
    Route::get('admin/admins',[AdminController::class,'all_admin']);
    Route::get('admin/countries',[AdminController::class,'all_country']);
    Route::get('admin/roles',[AdminController::class,'all_role'])->middleware('auth:admin');
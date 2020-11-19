<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('design/ads', [App\Http\Controllers\Design2Controller::class, 'ads']);
Route::get('category/json_categories', [App\Http\Controllers\CategoryController::class, 'json_categories']);

Route::get('admin/categories', [App\Http\Controllers\AdminController::class, 'categories']);
Route::match(['get', 'post'], 'admin/edit_category/{id?}', [App\Http\Controllers\AdminController::class, 'edit_category']);

// Service (diensten) controllers
Route::match(['get', 'post'], 'service/create', [App\Http\Controllers\ServiceController::class, 'edit']);
Route::match(['get', 'post'], 'service/edit/{id}', [App\Http\Controllers\ServiceController::class, 'edit']);
Route::match(['get', 'post'], 'service/view/{id}', [App\Http\Controllers\ServiceController::class, 'view']);
Route::get('service/overview', [App\Http\Controllers\ServiceController::class, 'overview']);

// Ad (opdrachten) controllers
Route::match(['get', 'post'], 'ad/create', [App\Http\Controllers\Ad2Controller::class, 'edit']);
Route::match(['get', 'post'], 'ad/edit/{id}', [App\Http\Controllers\Ad2Controller::class, 'edit']);
Route::match(['get', 'post'], 'ad/view/{id}', [App\Http\Controllers\Ad2Controller::class, 'view']);
Route::get('ad/overview', [App\Http\Controllers\Ad2Controller::class, 'overview']);
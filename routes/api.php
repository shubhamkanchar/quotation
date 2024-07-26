<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BusinessModelController;
use App\Http\Controllers\CustomerModelController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\TermsModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class,'register'])->name('api.register');
Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::resource('business',BusinessModelController::class);
    Route::resource('customer',CustomerModelController::class);
    Route::resource('product',ProductModelController::class);
    Route::resource('term',TermsModelController::class);
});

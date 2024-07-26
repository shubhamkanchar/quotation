<?php

use App\Http\Controllers\BusinessModelController;
use App\Http\Controllers\CustomerModelController;
use App\Http\Controllers\MakeDeliveryNoteController;
use App\Http\Controllers\MakeInvoiceController;
use App\Http\Controllers\MakeProformaInvoiceController;
use App\Http\Controllers\MakePurchaseOrderController;
use App\Http\Controllers\MakeQuotationController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\TermsModelController;
use App\Models\MakeQuotation;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/login-with-otp', [App\Http\Controllers\Auth\LoginController::class, 'loginWithOtp'])->name('loginWithOtp');

Route::middleware('auth')->group(function(){
    Route::resource('business',BusinessModelController::class);
    Route::resource('customer',CustomerModelController::class);
    Route::resource('product',ProductModelController::class);
    Route::resource('term',TermsModelController::class);

    Route::resource('make-quotation',MakeQuotationController::class);
    Route::resource('make-invoice',MakeInvoiceController::class);
    Route::resource('make-purchase-order',MakePurchaseOrderController::class);
    Route::resource('make-proforma-invoice',MakeProformaInvoiceController::class);
    Route::resource('make-delivery-note',MakeDeliveryNoteController::class);
});
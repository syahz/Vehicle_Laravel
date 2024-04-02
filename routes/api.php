<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
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
// Customer
Route::get('/customer', [CustomerController::class, 'index']);
Route::post('/customer', [CustomerController::class, 'store']);
Route::get('/customer/{id}', [CustomerController::class, 'show']);
// Vehicle
Route::get('/vehicle', [VehicleController::class, 'index']);
Route::post('/vehicle', [VehicleController::class, 'store']);
Route::get('/vehicle/{id}', [VehicleController::class, 'show']);
// Discount
Route::get('/discount', [DiscountController::class, 'index']);
Route::post('/discount', [DiscountController::class, 'store']);
// Transaction
Route::get('/transaction', [TransactionController::class, 'index']);
Route::post('/transaction', [TransactionController::class, 'store']);

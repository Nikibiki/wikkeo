<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\SellersController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/sellers', [SellersController::class, 'index']);
Route::post('/sellers', [SellersController::class, 'store']);
Route::get('/sellers/{id}', [SellersController::class, 'show']);
Route::patch('/sellers/{id}', [SellersController::class, 'update']);
Route::delete('/sellers/{id}', [SellersController::class, 'destroy']);

Route::get('/generate', [SellersController::class, 'fakeData']);

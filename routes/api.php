<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);

Route::prefix('example')->controller(App\Http\Controllers\ExampleController::class)->group(function () {
    // GET ALL WITH PAGINATION
    Route::get('/', 'index');
    // STORE
    Route::post('/', 'store');
    // UPDATE (WITH VALIDATION)
    Route::put('/{id}', 'update');
    // SHOW
    Route::get('/{id}', 'show');
    // UPDATE PARTIAL (WITHOUT VALIDATION)
    Route::patch('/{id}', 'updateFields');
    // DELETE
    Route::delete('/{id}', 'destroy');
});
Route::prefix('items')->controller(App\Http\Controllers\ItemController::class)->group(function () {
    // GET ALL WITH PAGINATION
    Route::get('/', 'index');
    // STORE
    Route::post('/', 'store');
    // UPDATE (WITH VALIDATION)
    Route::put('/{id}', 'update');
    // SHOW
    Route::get('/{id}', 'show');
    // UPDATE PARTIAL (WITHOUT VALIDATION)
    Route::patch('/{id}', 'updateFields');
    // DELETE
    Route::delete('/{id}', 'destroy');
});

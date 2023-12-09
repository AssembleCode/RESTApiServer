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

Route::prefix('example')->group(function () {
    // GET ALL WITH PAGINATION
    Route::get('/', [App\Http\Controllers\ExampleController::class, 'index']);
    // STORE
    Route::post('/', [App\Http\Controllers\ExampleController::class, 'store']);
    // UPDATE (WITH VALIDATION)
    Route::put('/{id}', [App\Http\Controllers\ExampleController::class, 'update']);
    // SHOW
    Route::get('/{id}', [App\Http\Controllers\ExampleController::class, 'show']);
    // UPDATE PARTIAL (WITHOUT VALIDATION)
    Route::patch('/{id}', [App\Http\Controllers\ExampleController::class, 'updateFields']);
    // DELETE
    Route::delete('/{id}', [App\Http\Controllers\ExampleController::class, 'destroy']);
});

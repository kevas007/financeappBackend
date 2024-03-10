<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ExpensesController;
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


// AuthenticatedSessionController

Route::post('/register', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])
                ->middleware('guest');



Route::middleware('auth:sanctum')->group(function () {
    // Placez ici les routes nécessitant une authentification Sanctum
    
    Route::post('/category', [CategoriesController::class, 'store']);
    Route::put('/category/{id}', [CategoriesController::class, 'update']);
    Route::get('/category/{id}', [CategoriesController::class, 'show']);
    Route::delete('/category/{id}', [CategoriesController::class, 'destroy']);
    //
    Route::post('/expense', [ExpensesController::class, 'store']);
    Route::put('/expense/{id}', [ExpensesController::class, 'update']);
    // Vous pouvez ajouter d'autres routes nécessitant une authentification Sanctum ici
});
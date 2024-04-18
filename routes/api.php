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

    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/allexpenses', [CategoriesController::class, 'allExpenses']);
    Route::get('/MonthlyExpenses', [CategoriesController::class, 'MonthlyExpenses']);
    // Placez ici les routes nécessitant une authentification Sanctum
    
    Route::post('/category/add', [CategoriesController::class, 'store']);
    Route::put('/category/{id}', [CategoriesController::class, 'update']);
    Route::get('/category/{id}/show', [CategoriesController::class, 'show']);
    Route::get('/category', [CategoriesController::class, 'index']);
    Route::delete('/category/{id}/delete', [CategoriesController::class, 'destroy']);
    //
    Route::get('/expenses', [ExpensesController::class, 'index']);
    Route::post('/expense/add', [ExpensesController::class, 'store']);
    Route::put('/expense/{id}/update', [ExpensesController::class, 'update']);
    Route::delete('/expense/{id}/delete', [ExpensesController::class, 'destroy']);
    // Vous pouvez ajouter d'autres routes nécessitant une authentification Sanctum ici
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\NomorController;

Route::get('/nomors', [NomorController::class, 'index']);
Route::post('/nomors', [NomorController::class, 'store']);
// Route::post('/nomors/multi-update', [NomorController::class, 'multiUpdate']);
Route::put('/nomors/update/{id}', [NomorController::class, 'Update']);
// Route::delete('/nomors/multi-delete', [NomorController::class, 'multiDelete']);
Route::delete('/nomors/delete/{id}', [NomorController::class, 'Delete']);
// Route::get('/nomors/search', [NomorController::class, 'search']);
Route::get('/nomors/search/{title}', [NomorController::class, 'search']);

Route::get('/nomors/generate-link', [NomorController::class, 'generateLink']);

Route::get('/nomors/show-link', [NomorController::class, 'showLink']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

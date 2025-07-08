<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;

Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->get('/user', [UserApiController::class, 'index'];
// });
// Route::apiResource('/user', UserApiController::class)->middleware('auth:sanctum');
// Route::apiResource('/user', UserApiController::class);
Route::get('/user', [UserApiController::class, 'index'])->middleware('auth:sanctum');
Route::post('/user', [UserApiController::class, 'store']);
Route::post('/user/{id}', [UserApiController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/user/{id}', [UserApiController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/user/{id}', [UserApiController::class, 'show'])->middleware('auth:sanctum');
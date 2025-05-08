<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Middleware\BasicAuthUsernameMiddleware;

Route::middleware(BasicAuthUsernameMiddleware::class)->apiResource('dosen', DosenController::class);

Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
Route::get('/mahasiswa/nim/{nim}', [MahasiswaController::class, 'showByNim']);
Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update']);
Route::put('/mahasiswa/nim/{nim}', [MahasiswaController::class, 'updateByNim']);
Route::delete('/mahasiswa/nim/{nim}', [MahasiswaController::class, 'destroyByNim']);

Route::middleware('api.key')->get('/data', function () {
    return response()->json([
        'status' => true,
        'message' => 'Access granted',
        'data' => 'Your protected data',
    ]);
});

// JWT Auth routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/protected', function () {
        return response()->json([
            'status' => true,
            'message' => 'You have accessed a protected route',
        ]);
    });
});




<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->prefix('auth')->group(function () {
    
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    
});

Route::middleware('auth:api')->get('/test-connection', function () {
    return response()->json(['message' => 'Bağlantı başarılı, token geçerli!']);
});

Route::middleware('auth:api')->group(function () {
    
    Route::get('/users', function() {
        return response()->json(['message' => 'Kullanıcılar listelendi.']);
    })->middleware('checkPermission:user-list');

    Route::delete('/users/{id}', function($id) {
        return response()->json(['message' => $id . ' IDli kullanıcı silindi.']);
    })->middleware('checkPermission:user-delete');

});
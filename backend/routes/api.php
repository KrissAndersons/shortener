<?php

use App\Http\Controllers\LinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/shorten', [LinkController::class, 'shorten']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

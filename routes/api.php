<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(WhatsAppController::class)->group(function () {
    Route::get('/webhook/whatsapp', 'verify');
    Route::post('/webhook/whatsapp', 'handle');
});

<?php
use App\Http\Controllers\Auth\QrLoginController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {
//     // API សម្រាប់ទទួល Token ពីការ Scan លើ Mobile App
//     Route::post('/qr/authorize', [QrLoginController::class, 'handleScan']);
// });
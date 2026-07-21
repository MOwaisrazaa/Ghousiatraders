<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\XSS;

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

// Apply XSS protection middleware to all API routes
Route::group(['middleware' => ['XSS']], function() {

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


}); // End of XSS protection middleware group
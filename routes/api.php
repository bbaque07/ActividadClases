<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//importar los controladores
use App\Http\Controllers\ParaleloController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::get('/retornar-paralelos',[ParaleloController::class ,'index']);
Route::post('/guardar-paralelos', [ParaleloController::class,'store']);
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FahmiController;
use App\Http\Controllers\FahmiGambarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fahmi', [FahmiController::class, 'index']);
Route::get('/fahmigambar', [FahmiGambarController::class, 'index']);

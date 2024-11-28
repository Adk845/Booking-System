<?php

use App\Http\Controllers\fullCalenderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [fullCalenderController::class, 'first']);
Route::get('/fullcalender', [fullCalenderController::class, 'index'])->name('index');
Route::post('/fullCalenderAjax', [fullCalenderController::class, 'ajax'])->name('ajax');
Route::get('/test', function() {
    return view('test');
});
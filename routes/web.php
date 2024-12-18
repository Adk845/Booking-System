<?php

use App\Http\Controllers\emailController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [fullCalenderController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');  
// Route::get('/fullcalender', [fullCalenderController::class, 'index'])->name('index');
// Route::post('/fullCalenderAjax', [fullCalenderController::class, 'ajax'])->name('ajax');

  
});

//api
Route::get('/dashboard_api', [fullCalenderController::class, 'dashboard_api'])->name('dashboard_data');
Route::get('/fullcalender', [fullCalenderController::class, 'index'])->name('index');
Route::post('/fullCalenderAjax', [fullCalenderController::class, 'ajax'])->name('ajax');

require __DIR__.'/auth.php';

//test
Route::get('/test', [fullCalenderController::class, 'test_view'])->name('test_view');
Route::post('/test', [fullCalenderController::class, 'test_send'])->name('test_send');

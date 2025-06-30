<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\UserController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// admin routes
Route::middleware(['CheckTypes:admin', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });
});


// user routes
Route::middleware(['CheckTypes:user', 'auth'])->prefix('user')->name('user.')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });
});


// manager routes
Route::middleware(['CheckTypes:manager', 'auth'])->prefix('manager')->name('manager.')->group(function () {
    Route::controller(ManagerController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });
});

require __DIR__.'/auth.php';

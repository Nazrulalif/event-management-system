<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\staff\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
    if (Auth::check()) {
        return redirect(route('dashboard.admin'));
    }
    return view('session.index');
})->name('login');

Route::get('/logout', function (Request $request) {
    try {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    } catch (\Exception $th) {
        return redirect()->back()->with("error", "Log out failed, kindly contact the Administrator!");
    }
})->name('logout');

Route::post('/login-post', [AuthController::class, 'post'])->name('login.post');

route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');
});

route::middleware(['auth', 'web', 'staff'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

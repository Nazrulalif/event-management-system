<?php

use App\Http\Controllers\admin\AgentController;
use App\Http\Controllers\admin\CalendarController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\MyProfileController;
use App\Http\Controllers\admin\StaffController;
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
    Route::get('/my-profile', [MyProfileController::class, 'index'])->name('profile.admin');
    Route::post('/my-profile-update', [MyProfileController::class, 'update'])->name('profile.admin.update');

    //calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/events', [CalendarController::class, 'getEvents'])->name('calendar.event');
    Route::post('/add-event', [CalendarController::class, 'store'])->name('events.store');
    Route::delete('/events/delete/{event}', [CalendarController::class, 'destroy']);

    //user management
    Route::get('/user-management', [StaffController::class, 'index'])->name('user.index');
    Route::post('/user-management-register', [StaffController::class, 'register'])->name('user.register');
    Route::post('/user-management/deactivate/{id}', [StaffController::class, 'deactivate'])->name('user.deactivate');

    Route::get('/user-detail/{id}', [StaffController::class, 'detail'])->name('user.detail');
    Route::post('/user-detail-update/{id}', [StaffController::class, 'update'])->name('user.detail.update');

    Route::get('/pending-request', [StaffController::class, 'pending'])->name('user.pending');
    Route::post('/pending-request/accept/{id}', [StaffController::class, 'accept'])->name('user.accept');
    Route::delete('/pending-request/reject/{id}', [StaffController::class, 'reject'])->name('user.rejct');

    //agent management
    Route::get('/agent-management', [AgentController::class, 'index'])->name('agent.index');
    Route::post('/agent-management/add', [AgentController::class, 'add'])->name('agent.add');
    Route::delete('/agent-management/delete/{id}', [AgentController::class, 'delete'])->name('agent.delete');

    Route::get('/agent-detail/{id}', [AgentController::class, 'detail'])->name('agent.detail');
    Route::post('/agent-detail-update/{id}', [AgentController::class, 'update'])->name('agent.detail.update');


    //event Management
    Route::get('/event-management', [EventController::class, 'index'])->name('event.index');
    Route::delete('/event-management/delete/{id}', [EventController::class, 'delete'])->name('event.delete');
    Route::get('/event-pending', [EventController::class, 'pending'])->name('event.pending');
    Route::post('/event-management/reject/{id}', [EventController::class, 'reject'])->name('event.reject');
    Route::post('/event-management/approve/{id}', [EventController::class, 'approve'])->name('event.approve');
    Route::get('/event-draft', [EventController::class, 'draft'])->name('event.draft');
    Route::get('/event-detail/{id}', [EventController::class, 'detail'])->name('calendar.detail');
});

route::middleware(['auth', 'web', 'staff'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

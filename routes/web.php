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
|---------------------------------------------------------------------------
|                  2024
|                 Credits
|---------------------------------------------------------------------------
| Developed by Muhammad Nazrul Alif
| GitHub: https://github.com/Nazrulalif/
| Website: https://nazrulalif.vercel.app/
| WhatsApp: 014-9209024
|
| Feel free to explore and contribute to this project!
|---------------------------------------------------------------------------
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
    Route::post('/my-profile-change-password', [MyProfileController::class, 'change_password'])->name('profile.admin.password');

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

    Route::get('/event-progress-main/{id}', [EventController::class, 'edit'])->name('event.progress');

    Route::PUT('/event-progress/main-update/{id}', [EventController::class, 'main_update'])->name('event.progress.main');
    Route::get('/events/{id}/check-progress-main', [EventController::class, 'checkProgress_main']);

    Route::get('/event-progress-schedule/{id}', [EventController::class, 'schedule'])->name('event.progress.schedule');
    Route::post('/event-progress/schedule-update/{id}', [EventController::class, 'schedule_update'])->name('event.schedule.update');
    Route::get('/events/{id}/check-progress-schedule', [EventController::class, 'checkProgress_schedule']);

    Route::get('/event-progress-reward/{id}', [EventController::class, 'reward'])->name('event.progress.reward');
    Route::post('/event-progress-reward-update/{id}', [EventController::class, 'reward_update'])->name('event.reward.update');
    Route::get('/events/{id}/check-progress-reward', [EventController::class, 'checkProgress_reward']);

    Route::get('/event-progress-target/{id}', [EventController::class, 'target'])->name('event.progress.target');
    Route::post('/event-progress-target-update/{id}', [EventController::class, 'target_update'])->name('event.target.update');
    Route::delete('/event-progress-target-delete/{id}', [EventController::class, 'target_delete'])->name('event.target.delete');
    Route::get('/events/{id}/check-progress-target', [EventController::class, 'checkProgress_target']);

    Route::get('/event-progress-budget/{id}', [EventController::class, 'budget'])->name('event.progress.budget');
    Route::post('/event-progress-budget-update/{id}', [EventController::class, 'budget_update'])->name('event.budget.update');
    Route::get('/events/{id}/check-progress-budget', [EventController::class, 'checkProgress_budget']);
});

route::middleware(['auth', 'web', 'staff'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

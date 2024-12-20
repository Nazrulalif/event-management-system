<?php

use App\Http\Controllers\admin\AgentController;
use App\Http\Controllers\admin\CalendarController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\admin\MyProfileController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\ViewEventController;
use App\Http\Controllers\agent\AgentController as AgentAgentController;
use App\Http\Controllers\agent\HomeController as AgentHomeController;
use App\Http\Controllers\agent\MyProfileController as AgentMyProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\staff\AgentController as StaffAgentController;
use App\Http\Controllers\staff\EventController as StaffEventController;
use App\Http\Controllers\staff\HomeController;
use App\Http\Controllers\staff\MyProfileController as StaffMyProfileController;
use App\Http\Controllers\staff\ReportController as StaffReportController;
use App\Http\Controllers\staff\ViewEventController as StaffViewEventController;
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
    // Check if the regular user is authenticated
    if (Auth::check()) {
        $user = Auth::user();
        return ($user->role_guid == 1)
            ? redirect()->route('dashboard.admin') // Redirect to admin dashboard
            : redirect()->route('home'); // Redirect to regular user home
    }
    if (Auth::guard('agent')->check()) {
        return redirect()->route('home.agent'); // Redirect to agent home/dashboard
    }
    return view('session.login');
})->name('login');

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect(route('dashboard.admin'));
    }
    return view('session.register');
})->name('register');

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
Route::post('/register-post', [AuthController::class, 'register'])->name('register.post');

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
    Route::get('/event-management/show/{id}', [EventController::class, 'show'])->name('event.show');
    Route::post('/event-management/update', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event-management/delete/{id}', [EventController::class, 'delete'])->name('event.delete');
    Route::get('/event-pending', [EventController::class, 'pending'])->name('event.pending');
    Route::post('/event-management/reject/{id}', [EventController::class, 'reject'])->name('event.reject');
    Route::post('/event-management/approve/{id}', [EventController::class, 'approve'])->name('event.approve');
    Route::get('/event-draft', [EventController::class, 'draft'])->name('event.draft');

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

    Route::get('/event-progress-staff-grouping/{id}', [EventController::class, 'staff'])->name('event.progress.staff');
    Route::post('/event-progress-staff-grouping-update/{id}', [EventController::class, 'staff_update'])->name('event.staff.update');
    Route::delete('/event-progress-staff-grouping-delete/{id}', [EventController::class, 'staff_delete'])->name('event.staff.delete');
    Route::get('/event-progress-staff-grouping-show/{id}', [EventController::class, 'staff_show'])->name('event.staff.show');
    Route::post('/event-progress-staff-grouping-edit-update', [EventController::class, 'staff_edit_update'])->name('event.staff.editUpdate');
    Route::get('/events/{id}/check-progress-staff', [EventController::class, 'checkProgress_staff']);

    Route::get('/event-progress-agent-grouping/{id}', [EventController::class, 'agent'])->name('event.progress.agent');
    Route::post('/event-progress-agent-grouping-update/{id}', [EventController::class, 'agent_update'])->name('event.agent.update');
    Route::delete('/event-progress-agent-grouping-delete/{id}', [EventController::class, 'agent_delete'])->name('event.agent.delete');
    Route::get('/event-progress-agent-grouping-show/{id}', [EventController::class, 'agent_show'])->name('event.agent.show');
    Route::post('/event-progress-agent-grouping-edit-update', [EventController::class, 'staff_agent_update'])->name('event.agent.editUpdate');
    Route::get('/events/{id}/check-progress-agent', [EventController::class, 'checkProgress_agent']);

    Route::get('/view-event/{id}', [ViewEventController::class, 'index'])->name('view.index');
    Route::get('/view-event-print/{id}', [ViewEventController::class, 'print'])->name('view.print');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/view-report', [ReportController::class, 'post'])->name('report.post');
    Route::get('/report-print', [ReportController::class, 'print'])->name('report.print');
});

route::middleware(['auth', 'web', 'staff'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/events', [HomeController::class, 'getEvents']);
    Route::get('/my-profile', [StaffMyProfileController::class, 'index'])->name('profile');
    Route::post('/my-profile-update', [StaffMyProfileController::class, 'update'])->name('profile.update');
    Route::post('/my-profile-change-password', [StaffMyProfileController::class, 'change_password'])->name('profile.password');


    Route::get('/view-event/{id}', [StaffViewEventController::class, 'index'])->name('view.index');
    Route::get('/view-event-print/{id}', [StaffViewEventController::class, 'print'])->name('view.print.user');

    //agent management
    Route::get('/agent-management', [StaffAgentController::class, 'index'])->name('agent.index.user');
    Route::post('/agent-management/add', [StaffAgentController::class, 'add'])->name('agent.add.user');
    Route::delete('/agent-management/delete/{id}', [StaffAgentController::class, 'delete'])->name('agent.delete');
    Route::get('/agent-detail/{id}', [StaffAgentController::class, 'detail'])->name('agent.detail');
    Route::post('/agent-detail-update/{id}', [StaffAgentController::class, 'update'])->name('agent.detail.update.user');

    Route::get('/event-management', [StaffEventController::class, 'index'])->name('event.index.user');
    Route::get('/event-management/show/{id}', [StaffEventController::class, 'show'])->name('event.show');
    Route::post('/event-management/update', [StaffEventController::class, 'update'])->name('event.update.user');
    Route::delete('/event-management/delete/{id}', [StaffEventController::class, 'delete'])->name('event.delete');
    Route::get('/event-draft', [StaffEventController::class, 'draft'])->name('event.draft.user');
    Route::post('/add-event', [StaffEventController::class, 'store'])->name('events.store.user');

    Route::get('/event-progress-main/{id}', [StaffEventController::class, 'edit'])->name('event.progress.user');

    Route::PUT('/event-progress/main-update/{id}', [StaffEventController::class, 'main_update'])->name('event.progress.main.user');
    Route::get('/events/{id}/check-progress-main', [StaffEventController::class, 'checkProgress_main']);

    Route::get('/event-progress-schedule/{id}', [StaffEventController::class, 'schedule'])->name('event.progress.schedule.user');
    Route::post('/event-progress/schedule-update/{id}', [StaffEventController::class, 'schedule_update'])->name('event.schedule.update.user');
    Route::get('/events/{id}/check-progress-schedule', [StaffEventController::class, 'checkProgress_schedule']);

    Route::get('/event-progress-reward/{id}', [StaffEventController::class, 'reward'])->name('event.progress.reward.user');
    Route::post('/event-progress-reward-update/{id}', [StaffEventController::class, 'reward_update'])->name('event.reward.update.user');
    Route::get('/events/{id}/check-progress-reward', [StaffEventController::class, 'checkProgress_reward']);

    Route::get('/event-progress-target/{id}', [StaffEventController::class, 'target'])->name('event.progress.target.user');
    Route::post('/event-progress-target-update/{id}', [StaffEventController::class, 'target_update'])->name('event.target.update.user');
    Route::delete('/event-progress-target-delete/{id}', [StaffEventController::class, 'target_delete'])->name('event.target.delete');
    Route::get('/events/{id}/check-progress-target', [StaffEventController::class, 'checkProgress_target']);

    Route::get('/event-progress-budget/{id}', [StaffEventController::class, 'budget'])->name('event.progress.budget.user');
    Route::post('/event-progress-budget-update/{id}', [StaffEventController::class, 'budget_update'])->name('event.budget.update.user');
    Route::get('/events/{id}/check-progress-budget', [StaffEventController::class, 'checkProgress_budget']);

    Route::get('/event-progress-staff-grouping/{id}', [StaffEventController::class, 'staff'])->name('event.progress.staff.user');
    Route::post('/event-progress-staff-grouping-update/{id}', [StaffEventController::class, 'staff_update'])->name('event.staff.update.user');
    Route::delete('/event-progress-staff-grouping-delete/{id}', [StaffEventController::class, 'staff_delete'])->name('event.staff.delete');
    Route::get('/event-progress-staff-grouping-show/{id}', [StaffEventController::class, 'staff_show'])->name('event.staff.show');
    Route::post('/event-progress-staff-grouping-edit-update', [StaffEventController::class, 'staff_edit_update'])->name('event.staff.editUpdate.user');
    Route::get('/events/{id}/check-progress-staff', [StaffEventController::class, 'checkProgress_staff']);

    Route::get('/event-progress-agent-grouping/{id}', [StaffEventController::class, 'agent'])->name('event.progress.agent.user');
    Route::post('/event-progress-agent-grouping-update/{id}', [StaffEventController::class, 'agent_update'])->name('event.agent.update.user');
    Route::delete('/event-progress-agent-grouping-delete/{id}', [StaffEventController::class, 'agent_delete'])->name('event.agent.delete');
    Route::get('/event-progress-agent-grouping-show/{id}', [StaffEventController::class, 'agent_show'])->name('event.agent.show');
    Route::post('/event-progress-agent-grouping-edit-update', [StaffEventController::class, 'staff_agent_update'])->name('event.agent.editUpdate.user');
    Route::get('/events/{id}/check-progress-agent', [StaffEventController::class, 'checkProgress_agent']);

    Route::get('/report', [StaffReportController::class, 'index'])->name('report.index.user');
    Route::get('/view-report', [StaffReportController::class, 'post'])->name('report.post.user');
    Route::get('/report-print', [StaffReportController::class, 'print'])->name('report.print.user');
});

route::prefix('agent')->middleware(['agent'])->group(function () {
    Route::get('/home', [AgentHomeController::class, 'index'])->name('home.agent');
    Route::get('/events', [AgentHomeController::class, 'getEvents']);
    Route::get('/my-profile', [AgentMyProfileController::class, 'index'])->name('profile.agent');
    Route::post('/my-profile-update', [AgentMyProfileController::class, 'update'])->name('profile.update.agent');
    Route::post('/my-profile-change-password', [AgentMyProfileController::class, 'change_password'])->name('profile.password.agent');

    Route::get('/view-event/{id}', [AgentHomeController::class, 'view'])->name('view.event.agent');
    Route::get('/view-event-print/{id}', [AgentHomeController::class, 'print'])->name('view.print.agent');

    //agent management
    Route::get('/agent-management', [AgentAgentController::class, 'index'])->name('agent.index.agent');
    Route::post('/agent-management/add', [AgentAgentController::class, 'add'])->name('agent.add.agent');
    Route::delete('/agent-management/delete/{id}', [AgentAgentController::class, 'delete'])->name('agent.delete');
    Route::get('/agent-detail/{id}', [AgentAgentController::class, 'detail'])->name('agent.agent');
    Route::post('/agent-detail-update/{id}', [AgentAgentController::class, 'update'])->name('agent.detail.update.agent');
});

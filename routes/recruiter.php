<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Recruiter\{
    RecruiterBlogController,
    RecruiterJobController,
    RecruiterDeviceController,
    RecruiterMessageController,
    RecruiterProfileController,
    RecruiterSubUserController,
    RecruiterDashboardController,
    RecruiterNotificationController,
    RecruiterJobApplicationController,
    RecruiterInterviewController
};
use Illuminate\Console\Scheduling\Schedule;

// Recruiter Authentication (Public)
Route::prefix('recruiter')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showRecruiterLoginForm'])->name('recruiter.login');
    Route::post('/login', [UserAuthController::class, 'recruiterLogin'])->name('recruiter.login.submit');
    Route::get('/register', [UserAuthController::class, 'showRecruiterRegisterForm'])->name('recruiter.register');
    Route::post('/register', [UserAuthController::class, 'recruiterRegister'])->name('recruiter.register.submit');

    // Forgot Password
    Route::get('/forgot/password', [UserAuthController::class, 'showRecruiterForgotPasswordForm'])->name('recruiter.password.request');
    Route::post('/send-otp', [UserAuthController::class, 'handleRecruiterForgotPassword'])->name('recruiter.password.send_otp');
    Route::post('/verify-otp', [UserAuthController::class, 'verifyRecruiterOtp'])->name('recruiter.password.verify_otp');
    Route::get('/reset-password', [UserAuthController::class, 'showRecruiterPasswordResetForm'])->name('recruiter.password.reset.form');
    Route::post('/update-password', [UserAuthController::class, 'updateRecruiterPassword'])->name('recruiter.password.reset.update');
});

// Recruiter Protected Routes (Requires auth + recruiter role)
Route::middleware(['role:recruiter'])->prefix('recruiter')->group(function () {
    // Route::get('test', fn()=> view('recruiter.test'));
    // Dashboard
    Route::get('/dashboard', [RecruiterDashboardController::class, 'recruiterDashboard'])->name('recruiter_dashboard');

    // Jobs
    Route::get('/all-jobs', [RecruiterJobController::class, 'allJobs'])->name('recruiter.all_jobs');
    Route::get('/create/job', fn() => view('recruiter.dashboard.create.create_job'))->name('create_job_view');
    Route::post('/create/job', [RecruiterJobController::class, 'createJob'])->name('create_job');
    Route::get('/jobs/{job}/edit', [RecruiterJobController::class, 'edit'])->name('recruiter.jobs.edit');
    Route::put('/jobs/{id}', [RecruiterJobController::class, 'update'])->name('recruiter.jobs.update');
    Route::delete('/jobs/{job}', [RecruiterJobController::class, 'destroy'])->name('recruiter.jobs.destroy');
    Route::patch('/jobs/{id}/toggle-status', [RecruiterJobController::class, 'toggleStatus'])->name('recruiter.jobs.toggleStatus');
    Route::get('/jobs/{id}', [RecruiterJobController::class, 'show'])->name('recruiter.jobs.show');

    // Applied Users
    Route::get('/job/applied/user', [RecruiterJobController::class, 'view_user'])->name('job.user');
    Route::post('/users/report/{id}', [RecruiterJobController::class, 'reportUser'])->name('users.report');
    Route::get('/users/block/{id}', [RecruiterJobController::class, 'blockUser'])->name('users.block');
    Route::get('/users/unblock/{id}', [RecruiterJobController::class, 'unblockUser'])->name('users.unblock');

    // Applications
    Route::get('/applications', [RecruiterJobApplicationController::class, 'index'])->name('job_application.index');
    Route::get('/applications/{id}', [RecruiterJobApplicationController::class, 'show'])->name('job_application.show');
    Route::put('/applications/{id}/status', [RecruiterJobApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::post('/job_application/{id}/message', [RecruiterMessageController::class, 'sendMessage'])->name('job_application.message');
    Route::get('/job_application/export', [RecruiterJobApplicationController::class, 'export'])->name('job_application.export');

    // Schedule Interview 
    Route::post('/recruiter/schedule-interview', [RecruiterInterviewController::class, 'schedule'])->name('recruiter.schedule.interview');
    Route::get('recruiter/interviews/', [RecruiterInterviewController::class, 'index'])->name('recruiter.interview.index');
    Route::put('recruiter/interviews/{interview}', [RecruiterInterviewController::class, 'update'])->name('recruiter.interviews.update');
    Route::delete('recruiter/interviews/{interview}', [RecruiterInterviewController::class, 'destroy'])->name('recruiter.interviews.destroy');
    Route::post('recruiter/interviews/{id}/cancel', [RecruiterInterviewController::class, 'cancelInterview'])->name('recruiter.interviews.cancel');
    Route::post('recruiter/interviews/{id}/reschedule', [RecruiterInterviewController::class, 'rescheduleInterview'])->name('recruiter.interviews.reschedule');
    
    // Account/Profile
    Route::get('/account/settings', [RecruiterProfileController::class, 'recruiterAccountSettings'])->name('recruiter.account.settings');
    Route::patch('/profile/update', [RecruiterProfileController::class, 'recruiter_update_profile'])->name('recruiter.profile.update');
    Route::put('/password/update', [RecruiterProfileController::class, 'updatePassword'])->name('recruiter.password.update');

    // Devices & Notifications
    Route::delete('/devices/{device}', [RecruiterDeviceController::class, 'logout'])->name('recruiter.device.logout');
    Route::patch('/notifications/{id}/read', [RecruiterNotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [RecruiterNotificationController::class, 'delete'])->name('notifications.delete');

    // Sub-users (Manage Access)
    Route::get('/sub-users', [RecruiterSubUserController::class, 'index'])->name('recruiter.subuser.index');
    Route::get('/sub-users/create', [RecruiterSubUserController::class, 'create'])->name('recruiter.subuser.create');
    Route::post('/sub-users', [RecruiterSubUserController::class, 'store'])->name('recruiter.subuser.store');
    Route::get('/sub-users/{id}/edit', [RecruiterSubUserController::class, 'edit'])->name('recruiter.subuser.edit');
    Route::put('/sub-users/{id}', [RecruiterSubUserController::class, 'update'])->name('recruiter.subuser.update');
    Route::delete('/sub-users/{id}', [RecruiterSubUserController::class, 'destroy'])->name('recruiter.subuser.destroy');

    Route::resource('blog', RecruiterBlogController::class)->except(['show']);
});

// Static Public Pages
Route::view('products', 'recruiter.products')->name('products');
Route::view('resources', 'recruiter.resources')->name('resources');
Route::view('hire', 'recruiter.home')->name('hire');

<?php

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\UserBlogController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserActionsController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\JobApplicationController;

/**
 * --------------------------------------------------------------------------
 * Job Seeker Routes
 * --------------------------------------------------------------------------
 */

// ======================
// Public Job Seeker Routes
// ======================
Route::get('/', [JobController::class, 'index'])->name('home');
Route::get('/job-detail/{id}', [JobController::class, 'show']);
Route::get('/job-detail/{id}/share', [JobController::class, 'share'])->name('job.share');
Route::get('/job/{id}/view', [JobController::class, 'fullView'])->name('job.full-view');
Route::get('/jobs/{job}', [JobController::class, 'fullView'])->name('job.full');
Route::get('company', [CompanyController::class, 'company'])->name('company');
Route::get('salaries', [SalariesController::class, 'salaries'])->name('salaries');
Route::get('search/jobs', [JobController::class, 'search'])->name('job.search');

Route::get('/blogs', [UserBlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [UserBlogController::class, 'show'])->name('blog.show');
Route::post('/blogs/{id}/like', [UserBlogController::class, 'like'])->name('blogs.like');
Route::post('/blogs/{id}/dislike', [UserBlogController::class, 'dislike'])->name('blogs.dislike');
Route::post('/blogs/{slug}/comment', [UserBlogController::class, 'storeComment'])->name('blogs.comment.store');
Route::get('blog/{slug}/comments/load', [UserBlogController::class, 'loadMoreComments'])->name('blog.comment.load');



Route::get('/pages', [StaticPageController::class, 'publicIndex'])->name('pages.index');
Route::get('/page/{slug}', [StaticPageController::class, 'show'])->name('pages.show');

// ======================
// Job Seeker Auth Routes
// ======================
Route::get('user/login', [UserAuthController::class, 'showLoginForm'])->name('user_login');
Route::post('user/login', [UserAuthController::class, 'login']);
Route::get('user/signup', [UserAuthController::class, 'showRegisterForm'])->name('user_signup');
Route::post('user/signup', [UserAuthController::class, 'register']);


// Job Seeker Forgot Password
Route::get('/user/forgot/password', [UserAuthController::class, 'showJobSeekerForgotPasswordForm'])->name('jobseeker.password.request');
Route::post('/user/send-otp', [UserAuthController::class, 'handleJobSeekerForgotPassword'])->name('jobseeker.password.send_otp');
Route::post('/user/verify-otp', [UserAuthController::class, 'verifyJobSeekerOtp'])->name('jobseeker.password.verify_otp');
Route::get('/user/reset-password', [UserAuthController::class, 'showJobSeekerPasswordResetForm'])->name('jobseeker.password.reset.form');
Route::post('/user/update-password', [UserAuthController::class, 'updateJobSeekerPassword'])->name('jobseeker.password.reset.update');


// ===============================
// Authenticated Job Seeker Routes
// ===============================
Route::middleware(['role:job_seeker'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/saved', [JobController::class, 'saved'])->name('saved');


    Route::get('/apply/{id}', [JobController::class, 'applyView'])->name('job.apply');
    Route::post('/jobs/{id}/apply', [JobController::class, 'applySubmit'])->name('jobs.apply.submit');

    Route::get('/job_apply_success', [JobController::class, 'applySuccess'])->name('job.apply.success');

    Route::get('/my-interviews', [InterviewController::class, 'myScheduledInterviews'])->name('interviews');
    Route::get('/interview/{id}/reschedule', [InterviewController::class, 'showRescheduleForm'])->name('interviews.reschedule');
    Route::post('/interview/{id}/reschedule', [InterviewController::class, 'rescheduleInterview'])->name('interviews.reschedule.submit');
    Route::post('/interview/{id}/cancel', [InterviewController::class, 'cancelInterview'])->name('interviews.cancel');

    Route::get('/interview/{id}/cancel', [InterviewController::class, 'cancelInterview'])->name('interviews.cancel');

    // Withdraw Application
    Route::delete('/user/job/{id}/withdraw', [JobApplicationController::class, 'withdraw'])->name('user.job.withdraw');
    Route::delete('/user/job/delete/{id}', [JobApplicationController::class, 'destroy'])->name('user.job.delete');

    Route::get('conversations/', [MessageController::class, 'index'])->name('user.conversations');
    Route::get('conversations/{application}', [MessageController::class, 'show'])->name('user.conversations.show');
    Route::post('conversations/{application}/send', [MessageController::class, 'sendMessage'])->name('user.conversations.send');

    Route::get('/notifications', [NotificationsController::class, 'getNotification'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.read');

    Route::post('/review/{job}', [ReviewController::class, 'store'])->name('user.review.submit');

    Route::get('user/settings', [ProfileController::class, 'settings'])->name('user.settings');
    Route::post('user/settings/profile', [ProfileController::class, 'updateProfile'])->name('user.settings.profile.update');
    Route::post('user/settings/resume', [ProfileController::class, 'uploadResume'])->name('user.settings.resume.upload');
    Route::delete('user/settings/resume', [ProfileController::class, 'deleteResume'])->name('user.settings.resume.delete');
    Route::post('user/settings/security', [ProfileController::class, 'updatePassword'])->name('user.settings.security.update');
    Route::post('user/settings/account', [ProfileController::class, 'updateAccount'])->name('user.settings.account.update');
    Route::post('/account/deactivate', [ProfileController::class, 'deactivate'])->name('user.account.deactivate');

    Route::delete('user/settings/account', [ProfileController::class, 'deleteAccount'])->name('user.settings.account.delete');
});

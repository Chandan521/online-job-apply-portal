<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\JobManagement;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\BannedIpController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\UserReportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminCandidateController;

// Admin Routes
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/recent/application', [DashboardController::class, 'recent_application'])->name('applications.recent_application');
    Route::get('admin/site-traffic', [DashboardController::class, 'getSiteTraffic'])->name('admin.site_traffic');

    Route::get('admin/jobs', [JobManagement::class, 'index'])->name('admin.jobs.index');
    Route::post('/admin/jobs/{job}/approve', [JobManagement::class, 'approve'])->name('admin.jobs.approve');
    Route::post('/admin/jobs/{job}/reject', [JobManagement::class, 'reject'])->name('admin.jobs.reject');

    Route::get('admin/jobs/create', [JobManagement::class, 'create'])->name('admin.jobs.create');
    Route::post('admin/jobs', [JobManagement::class, 'store'])->name('admin.jobs.store');
    Route::get('admin/jobs/{job}/edit', [JobManagement::class, 'edit'])->name('admin.jobs.edit');
    Route::put('admin/jobs/{job}', [JobManagement::class, 'update'])->name('admin.jobs.update');
    Route::delete('admin/jobs/{job}', [JobManagement::class, 'destroy'])->name('admin.jobs.destroy');
    // Toggle job status (active/inactive)
    Route::patch('admin/jobs/{job}/status', [JobManagement::class, 'toggleStatus'])->name('admin.jobs.toggleStatus');

    // Job Application Routes
    Route::get('admin/applications', [AdminApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('admin/jobs/{id}', [AdminApplicationController::class, 'job_view'])->name('admin.jobs.show');
    // User Management
    Route::resource('admin/users', UserController::class);
    
    Route::get('admin/users/{id}', [AdminApplicationController::class, 'user_view'])->name('admin.users.show');
    Route::get('admin/recruiters/{id}', [AdminApplicationController::class, 'recruiters_view'])->name('admin.recruiters.show');






    Route::get('admin/candidates',[AdminCandidateController::class, 'index'])->name('admin.candidates');

    Route::get('admin/companies', [CompanyController::class, 'index'])->name('admin.companies');

    Route::get('admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('/admin/analytics/export', [AnalyticsController::class, 'exportCSV'])->name('admin.analytics.export');

    Route::get('admin/settings', [AdminSettingsController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings/update', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::resource('admin/static_pages', StaticPageController::class)->only([
        'index',
        'create',
        'store',
        'edit',
        'update',
        'show',
        'destroy'
    ]);


    // Profile Routes
    Route::get('admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('admin/profile/show', [AdminProfileController::class, 'show'])->name('admin.profile.show');
    Route::post('admin/profile/remove-photo', [AdminProfileController::class, 'removePhoto'])->name('admin.profile.remove_photo');

    // Help Center
    Route::get('admin/help', function () {
        return view('admin.help.index');
    })->name('admin.help');

    
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('banned_ips', BannedIpController::class)->only(['index', 'store', 'destroy']);
    Route::get('user-reports', [UserReportController::class, 'index'])->name('reports.index');
    Route::post('user-reports/{id}/ban', [UserReportController::class, 'ban'])->name('reports.ban');
    Route::resource('static_pages', StaticPageController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('posts', PostController::class);
    Route::resource('blog', BlogController::class)->except(['show']);
});


// Login and Register Routes
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.auth');
Route::post('admin/login', [AdminController::class, 'admin_login'])->name('admin.login');
Route::post('admin/register', [AdminController::class, 'admin_register'])->name('admin.register');

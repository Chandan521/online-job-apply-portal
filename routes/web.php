<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\UserAuthController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/pdf/resume/{filename}', function ($filename) {
    $path = storage_path('app/public/resumes/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Access-Control-Allow-Origin' => '*', // Allows fetch by PDF.js
    ]);
})->name('pdf.resume');

Route::middleware(['maintenance'])->group(function () {
    // Job Seeker Routes
    require __DIR__ . '/job_seeker.php';

    // Recruiter Routes
    require __DIR__ . '/recruiter.php';
});

// Admin Routes (no maintenance check)
require __DIR__ . '/admin.php';
// Post Login Redirect Logic
Route::get('/redirect-after-login', function () {
    $user = Auth::user();
    return match ($user->role) {
        'recruiter' => redirect()->route('recruiter_dashboard'),
        'job_seeker' => redirect()->route('home'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect('/'),
    };
})->middleware('auth')->name('redirect.after.login');

// Universal Routes
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// Clear cache and config Routes
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');
    return back()->with('success', 'Cache cleared successfully!');
})->name('clear');
// Eror Routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

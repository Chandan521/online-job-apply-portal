<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\JobApplication;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ Pusher config from DB
        $this->app->booted(function () {
            Config::set('broadcasting.connections.pusher.key', setting('pusher_key', config('broadcasting.connections.pusher.key')));
            Config::set('broadcasting.connections.pusher.secret', setting('pusher_secret', config('broadcasting.connections.pusher.secret')));
            Config::set('broadcasting.connections.pusher.app_id', setting('pusher_app_id', config('broadcasting.connections.pusher.app_id')));
            Config::set('broadcasting.connections.pusher.options.cluster', setting('pusher_cluster', config('broadcasting.connections.pusher.options.cluster')));
            Config::set('broadcasting.connections.pusher.host', setting('pusher_host', config('broadcasting.connections.pusher.host')));
        });


        // ⚠️ Special handling for useTLS (ensure it's boolean)

    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {

        View::composer('*', function ($view) {
            $totalUnread = 0;
            $notificationUnread = 0;

            if (Auth::check()) {
                $user = Auth::user();

                // ✅ Notifications count (applies to both roles)
                $notificationUnread = Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                if ($user->role === 'job_seeker') {
                    // ✅ Unread recruiter messages
                    $applications = JobApplication::with('messages')
                        ->where('user_id', $user->id)
                        ->get();

                    $totalUnread = $applications->sum(function ($app) use ($user) {
                        return $app->messages
                            ->where('sender_id', '!=', $user->id)
                            ->where('is_read', false)
                            ->count();
                    });
                } elseif ($user->role === 'recruiter') {
                    // ✅ Unread job seeker messages
                    $applications = JobApplication::with('messages')
                        ->whereHas('job', function ($q) use ($user) {
                            $q->where('recruiter_id', $user->id);
                        })
                        ->get();

                    $totalUnread = $applications->sum(function ($app) use ($user) {
                        return $app->messages
                            ->where('sender_id', '!=', $user->id)
                            ->where('is_read', false)
                            ->count();
                    });
                }
            }

            $view->with([
                'totalUnread' => $totalUnread,
                'totalUnreadNotifications' => $notificationUnread,
            ]);
        });


        if (app()->runningInConsole()) return;
        app()->booted(function () {
            Config::set('mail.mailers.smtp.host', setting('mail_host', config('mail.mailers.smtp.host')));
            Config::set('mail.mailers.smtp.port', setting('mail_port', config('mail.mailers.smtp.port')));
            Config::set('mail.mailers.smtp.username', setting('mail_username', config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', setting('mail_password', config('mail.mailers.smtp.password')));
            Config::set('mail.from.address', setting('mail_from_address', config('mail.from.address')));
            Config::set('mail.from.name', setting('mail_from_name', config('mail.from.name')));
            Config::set('mail.default', setting('mail_mailer', config('mail.default')));
        });
        Paginator::useBootstrap();

        Broadcast::routes();

        require base_path('routes/channels.php'); // ✅ Add this line if missing
    }
}

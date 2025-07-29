<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Setting;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use App\Events\MailSettingUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class AdminSettingsController extends Controller
{
    public function settings()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        if ($request->has('action')) {
            $action = $request->input('action');

            if ($action === 'migrate') {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                    return back()->with('success', 'Database migration completed successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', 'Migration failed: ' . $e->getMessage());
                }
            }

            if ($action === 'seed') {
                try {
                    Artisan::call('db:seed', ['--force' => true]);
                    return back()->with('success', 'Database seeding completed successfully!');
                } catch (\Exception $e) {
                    return back()->with('error', 'Seeding failed: ' . $e->getMessage());
                }
            }
        }

        $data = $request->only([
            'site_name',
            'site_email',
            'maintenance_mode',
            'mail_enabled',
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_from_address',
            'db_connection',
            'db_host',
            'db_port',
            'db_username',
            'db_password',
            'db_database',
            'app_url',
            'app_debug',

            // Pusher settings
            'pusher_app_id',
            'pusher_app_key',
            'pusher_app_secret',
            'pusher_app_cluster',
            'pusher_host',
            'pusher_port',
            'pusher_scheme',
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $logoPath]);
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => $faviconPath]);
        }

        if ($request->has('mail_enabled')) {
            event(new MailSettingUpdated(setting('mail_enabled')));
        }

        Artisan::call('config:clear');

        return back()->with('success', 'Settings updated successfully!');
    }
}

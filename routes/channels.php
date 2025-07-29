<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('application.{id}', function ($user, $id) {
    return (int) $user->id === (int) \App\Models\JobApplication::find($id)?->user_id;
});

Broadcast::channel('job-application.{id}', function ($user, $id) {
    return true; // Or check permissions here
});
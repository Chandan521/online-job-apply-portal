<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduledInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'job_id',
        'recruiter_id',
        'created_by',
        'interview_datetime',
        'mode',
        'location',
        'notes',
        'status',
    ];

    protected $casts = [
        'interview_datetime' => 'datetime',
    ];

    // Relationships

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobApplication()
    {
        return $this->belongsTo(\App\Models\JobApplication::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'city',
        'country_code',
        'phone',
        'resume',
        'status',
        'cover_letter'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function messages()
    {
        return $this->hasMany(ApplicationMessage::class);
    }
    public function answers()
    {
        return $this->hasMany(JobApplicationAnswer::class, 'application_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'location',
        'salary',
        'type',
        'shift',
        'skills',
        'company_logo',
        'cover_image',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'application_url',
        'deadline',
        'is_remote',
        'employment_level',
        'industry',
        'experience',
        'education',
        'recruiter_id',
        'views',
        'status',
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'deadline' => 'date',
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
    public function additionalQuestions()
    {
        return $this->hasMany(JobAdditionalQuestion::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

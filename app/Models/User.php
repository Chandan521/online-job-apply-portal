<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'country',
        'city',
        'address',
        'education',
        'experience',
        'linkedin_url',
        'about_me',
        'skills',
        'resume',
        'profile_photo',
        'status',
        'last_login_at',
        'last_login_ip',
        'profile_visits',
        'parent_id',
        'permissions'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'permissions' => 'array',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function subUsers()
    {
        return $this->hasMany(User::class, 'parent_id');
    }
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'user_id');
    }
    public function jobs()
    {
        return $this->hasMany(Job::class, 'recruiter_id');
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isRecruiter()
    {
        return $this->role === 'recruiter';
    }
    public function isJobSeeker()
    {
        return $this->role === 'job_seeker';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAdditionalQuestion extends Model
{
    protected $fillable = ['job_id', 'question', 'type', 'is_required', 'options'];
    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}

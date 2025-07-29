<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationAnswer extends Model
{
    protected $fillable = ['application_id', 'question_id', 'answer'];
    protected $casts = ['answer' => 'array'];

    public function question()
    {
        return $this->belongsTo(JobAdditionalQuestion::class, 'question_id');
    }

    public function application()
    {
        return $this->belongsTo(JobApplication::class);
    }
}

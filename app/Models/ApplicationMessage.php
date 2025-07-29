<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationMessage extends Model
{
    use SoftDeletes;
    protected $fillable = ['job_application_id', 'sender_id', 'message'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function application()
    {
        return $this->belongsTo(JobApplication::class);
    }
}

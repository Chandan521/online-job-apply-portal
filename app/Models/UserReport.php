<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserReport extends Model
{
    use HasFactory;
    protected $fillable = ['to_user_id', 'form_user_id', 'reason', 'details'];
     // Reported user (user being reported)
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // Reporter (user who reported)
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}

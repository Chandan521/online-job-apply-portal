<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    protected $table = "user_blocks";
    protected $fillable = ['from_user_id', 'to_user_id'];
}

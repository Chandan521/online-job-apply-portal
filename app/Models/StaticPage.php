<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
     protected $fillable = ['title', 'slug', 'content', 'is_visible'];
}

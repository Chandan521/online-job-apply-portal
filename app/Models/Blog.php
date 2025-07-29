<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['user_id', 'user_type', 'title', 'content', 'slug', 'featured_image'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // app/Models/Blog.php
    public function comments()
    {
        return $this->hasMany(BlogComment::class)->latest();
    }
}

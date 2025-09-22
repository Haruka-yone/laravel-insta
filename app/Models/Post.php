<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    // To get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
        // This will show the posts of inactive user
    }

    // To get the categories under a post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    // To get all the comments of post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // To get the likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Returns TRUE if the logged in user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }

    //multiple image
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }


}

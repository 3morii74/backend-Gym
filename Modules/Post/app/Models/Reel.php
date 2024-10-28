<?php

namespace Modules\Post\Models;

use App\Http\Models\Gallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;


// use Modules\Post\Database\Factories\ReelFactory;

class Reel extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'video_url', 'description', 'reel_id', 'views_count', 'likes_count', 'comments_count', 'shares_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reel()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }
    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }

    public function viewsCount()
    {
        return $this->views()->count();
    }
}

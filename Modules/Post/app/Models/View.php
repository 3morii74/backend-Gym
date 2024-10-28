<?php

namespace Modules\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

// use Modules\Post\Database\Factories\ViewFactory;

class View extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'reel_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function reel()
    {
        return $this->belongsTo(Reel::class);
    }
}

<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Set extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['user_id', 'user_exercise_id', 'reps', 'weight'];

    public function userExercise()
    {
        return $this->belongsTo(UserExercise::class);
    }
}

<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Exercise\Database\Factories\UserExerciseFactory;

class UserExercise extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'exercise_id', 'user_system_exercise_id'];

    public function userSystemExercise()
    {
        return $this->belongsTo(UserSystemExercise::class, 'user_system_exercise_id');
    }
    public function defaultExercise()
    {
        return $this->belongsTo(DefaultExercise::class, 'exercise_id');
    }
}

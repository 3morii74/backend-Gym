<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

class CustomizedExercise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'user_id', 'muscle_id', 'strength_percentage'];

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_customized_exercise', 'customized_exercise_id', 'muscle_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exerciseSystems()
    {
        return $this->morphToMany(ExerciseSystemCustomized::class, 'exerciseable', 'exercise_system_exercise')->withTimestamps(); ;
    }
}

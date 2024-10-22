<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultExercise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'strength_percentage', 'muscle_id'];

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_default_exercise', 'default_exercise_id', 'muscle_id')->withTimestamps();
    }

    public function exerciseSystems()
    {
        return $this->morphToMany(ExerciseSystemDefault::class, 'exerciseable', 'exercise_system_exercise')->withTimestamps();
    }
    public function userExercise()
    {
        return $this->hasMany(UserExercise::class, 'id');
    }
}

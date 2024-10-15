<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomizedExercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id', 'strength_percentage'];

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_customized_exercise');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function exerciseSystems()
    {
        return $this->belongsToMany(ExerciseSystemCustomized::class, 'exercise_system_defaults_customized_exercise')
            ->withTimestamps();
    }
}

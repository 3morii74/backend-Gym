<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultExercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description','strength_percentage'];

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_default_exercise');
    }

    public function exerciseSystems()
    {
        return $this->belongsToMany(ExerciseSystemDefault::class, 'exercise_system_defaults_default_exercise')
                    ->withTimestamps();
    }

    public function customizedExerciseSystems()
    {
        return $this->belongsToMany(ExerciseSystemDefault::class, 'exercise_system_defaults_customized_exercise')
                    ->withTimestamps();
    }
}

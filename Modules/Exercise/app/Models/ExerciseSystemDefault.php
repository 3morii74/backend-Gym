<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseSystemDefault extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function defaultExercises()
    {
        return $this->belongsToMany(DefaultExercise::class, 'exercise_system_defaults_default_exercise');
    }

    public function customizedExercises()
    {
        return $this->belongsToMany(CustomizedExercise::class, 'exercise_system_defaults_customized_exercise');
    }
}

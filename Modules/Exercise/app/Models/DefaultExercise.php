<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultExercise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description','strength_percentage'];

    public function muscles()
    {
        return $this->belongsToMany(Muscle::class, 'muscle_default_exercise');
    }

    public function exerciseSystems()
    {
        return $this->morphToMany(ExerciseSystemDefault::class, 'exerciseable', 'exercise_system_exercise');
    }
}

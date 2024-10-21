<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseSystemDefault extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];

    public function defaultExercises()
    {
        return $this->morphedByMany(DefaultExercise::class, 'exerciseable', 'exercise_system_exercise');
    }

  
   
}

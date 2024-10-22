<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

class ExerciseSystemDefault extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
    ];
    public function defaultExercises()
    {
        return $this->morphedByMany(DefaultExercise::class, 'exerciseable', 'exercise_system_exercise')->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_system_exercises')->withTimestamps();
    }
    
  
}

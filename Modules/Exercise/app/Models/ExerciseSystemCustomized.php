<?php

namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseSystemCustomized extends Model
{

    use HasFactory;
    use SoftDeletes;
    // Define the table name if it's not the plural form of the model name
    protected $table = 'exercise_system_customizeds';

    // Specify fillable fields
    protected $fillable = [
        'name',
        'description',
    ];


    public function customizedExercises()
    {
        return $this->morphedByMany(CustomizedExercise::class, 'exerciseable', 'exercise_system_exercise')->withTimestamps();
    }

    /**
     * Relationship: One-to-Many with UserSystemExercise
     * An ExerciseSystemCustomized can be associated with many UserSystemExercises.
     */
    // public function userSystemExercises()
    // {
    //     return $this->hasMany(UserSystemExercise::class, 'system_id');
    // }
}

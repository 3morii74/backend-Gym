<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseSystemCustomized extends Model
{
    // Define the table name if it's not the plural form of the model name
    protected $table = 'exercise_system_customizeds';

    // Specify fillable fields
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: One-to-Many with CustomizedExercises
     * An ExerciseSystemCustomized can have many customized exercises.
     */
    public function customizedExercises()
    {
        return $this->belongsToMany(CustomizedExercise::class, 'customized_exercise_exercise_system_customized', 'system_id', 'exercise_id')
                    ->withPivot('strength_percentage'); // Include pivot column if necessary
    }

    /**
     * Relationship: One-to-Many with UserSystemExercise
     * An ExerciseSystemCustomized can be associated with many UserSystemExercises.
     */
    public function userSystemExercises()
    {
        return $this->hasMany(UserSystemExercise::class, 'system_id');
    }
}
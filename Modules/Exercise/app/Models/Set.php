<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    protected $fillable = ['user_system_exercise_id', 'reps', 'weight'];

    public function userSystemExercise()
    {
        return $this->belongsTo(UserSystemExercise::class);
    }
}

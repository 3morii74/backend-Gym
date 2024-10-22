<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

class UserSystemExercise extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'exercise_system_default_id',
        'exercise_system_customized_id',
    ];

    public function exerciseSystem()
    {
        return $this->belongsTo(ExerciseSystemDefault::class, 'system_id');
    }
    public function userExercises()
    {
        return $this->hasMany(UserExercise::class, 'user_system_exercise_id');
    }
}

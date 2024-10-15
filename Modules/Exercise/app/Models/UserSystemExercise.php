<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSystemExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_system_default_id',
        'exercise_system_customized_id',
        'exercise_id',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function exerciseSystemDefault()
    {
        return $this->belongsTo(ExerciseSystemDefault::class);
    }

    public function exerciseSystemCustomized()
    {
        return $this->belongsTo(ExerciseSystemCustomized::class);
    }

    public function sets()
    {
        return $this->hasMany(Set::class);
    }
}

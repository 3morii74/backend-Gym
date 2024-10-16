<?php
namespace Modules\Exercise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Muscle extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'muscle_category_id'];

    public function category()
    {
        return $this->belongsTo(MuscleCategory::class, 'muscle_category_id');
    }

    public function defaultExercises()
    {
        return $this->belongsToMany(DefaultExercise::class, 'muscle_default_exercise');
    }

    public function customizedExercises()
    {
        return $this->belongsToMany(CustomizedExercise::class, 'muscle_customized_exercise');
    }
}
